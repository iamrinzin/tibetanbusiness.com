<template>
    <div style="min-height:80vh">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-12 mx-auto">
                    <div class="row">
                        <div class="col-md-3 my-1">
                            <div class="card p-3" style="padding-bottom:0px !important" id="dropdown_lists">
                                <form @submit.prevent="search_result()">
                                    <small class="text-muted font-weight-bolder" style="cursor:pointer" data-toggle="collapse" data-target="#search_collapse" aria-expanded="false" aria-controls="collapseExample">
                                        Filter: <i class="fas fa-sliders-h mx-1"></i>
                                    </small>
                                    <div class="collapse" id="search_collapse">
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 py-1">
                                        <input type="text"  v-model="filter.name" class="form-control" placeholder="Name">
                                        </div>
                                        <div class="col-md-12 col-sm-12 py-1">
                                            <input type="text" @keyup="load_location()"  v-model="filter.location" class="rounded form-control "  placeholder="Location" aria-label="Location">
                                            <ul class="w-100" id="myMap_location_dropdown" style="position: absolute;z-index:100;height:auto;font-size:12px">
                                                <li style="list-style:none;cursor:pointer"  class="py-2 text-dark border-bottom bg-light" v-for="(place,index) in places" @click="set_location(place.placeName,place.placeAddress)" v-if="index <= 5">
                                                    <span class="font-weight-bold text-dark">{{place.placeName}}</span>
                                                    <span class="d-block text-muted" style="font-size:12px">{{place.placeAddress}}</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="col-md-12 col-sm-12 py-1">
                                            <input type="text" @keyup="load_categories()" v-model="filter.category" class="rounded form-control" placeholder="Category" aria-label="Service type">
                                                <ul class="w-100" id="event_category_list" style="position: absolute;z-index:100;height:auto;font-size:14px">
                                                    <li class="font-weight-bold py-1" v-for="category in categories" :value="category.name" @click="set_category(category.name)">{{category.name}}</li>
                                                </ul>
                                        </div>
                                    </div>
                                    <div class="row py-2">
                                        <div class="col-md-12 col-sm-12 py-2">
                                            <div class="row">
                                                <div class="col-12"> 
                                                    <label for="from" class="small text-muted">From</label>
                                                    <input type="date" v-model="filter.from" class="form-control" id="from" name="from" value="2021-07-22">
                                                </div>
                                                <div class="col-12">
                                                    <label for="to" class="text-muted small">to</label>
                                                    <input type="date" v-model="filter.to"  class="form-control" id="to" name="to">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12 col-sm-12 py-2" v-show="status">
                                            <small class="small text-muted">Entry Fee:₹ <span class="text-muted"></span></small>
                                            <input type="text"  id="entry_fee" class="small text-muted my-2" readonly  style="border:0;">
                                            <div id="slider-range"></div>
                                        </div>
                                        <div class="col-12 py-2">
                                            <form class="was-validated">
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" class="custom-control-input" id="entry_free" v-model="filter.entry_status" @change="entry_free_status(status)">
                                                    <label class="custom-control-label text-muted" for="entry_free">Entry Free</label>
                                                    <div class="invalid-feedback">Example invalid feedback text</div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="col-md-12 py-2 text-center">
                                            <button class="btn btn-danger btn-md w-25"><small class="fas fa-search"></small></button>
                                            <button class="btn btn-secondary btn-md w-50" @click="reset()"><small>Reset</small></button>
                                        </div>
                                    </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-5" id="search">
                            <div class="alert alert-light" role="alert">
                                Total Result : {{total}} {{empty_result}}
                            </div>
                            <div class="col-md-12 py-2">
                                <p class="small text-muted pb-0 mb-1">Search keywords:</p>
                                <small v-if="filter.name" class="badge badge-secondary mb-1">Name: {{filter.name}}</small>
                                <small v-if="filter.location" class="badge badge-secondary mb-1">Location: {{filter.location}}</small>
                                <small v-if="filter.entry_fee" class="badge badge-secondary mb-1">Entry Fee: {{filter.fee_min}}</small>
                                <small v-if="filter.category" class="badge badge-secondary mb-1">Category: {{filter.category}}</small>
                                <small v-if="filter.from || filter.to" class="badge badge-secondary mb-1">Date: {{filter.from}} - {{filter.to}}</small>
                            </div>
                            <!-- Result -->
                            <div class="col-12" v-if="loading_placeholder">
                                <lazy-loading></lazy-loading>
                                <lazy-loading></lazy-loading>
                                <lazy-loading></lazy-loading>
                            </div>
                            <div v-else>
                                <div class="row" id="result">
                                    <div class="col-md-12 col-sm-12 col-xs-12 info my-2" v-for="(event,index) in events">
                                        <a v-bind:href="'/event/'+event.id">
                                            <div class="banner" v-bind:style='{ backgroundImage: `url(/storage/Event/Banner/${event.banner})`}'>

                                            <ul>
                                                <li class="btn btn-danger btn-md small" v-if="event.entry_fee > 0">Fee:&#x20B9 {{event.entry_fee}}/-</span></li>
                                                <li class="btn small btn-success btn-md " v-else>Free Entry</span></li>
                                                <li class="btn small btn-danger btn-md" v-if="event.end_date">{{event.end_date |date}}</li>
                                                <li class="btn small btn-danger btn-md" v-else>{{event.start_date |date}}</li>
                                                <li class="btn small btn-danger btn-md">{{event.category}}</li>
                                            </ul>
                                        </div>
                                        <div class="rate btn btn-warning btn-sm small" v-if="event.interested >0"><span><i class="far fa-thumbs-up text-white  mr-1"></i>{{event.interested}} Interested</span></div>
                                        </a>
                                        <div class="card px-2">
                                            <h6 class="text-dark pt-3 font-weight-bolder">{{event.name}}</h6>
                                            <p class="text-muted my-0">{{event.mobile_no}}</p>
                                            <p class="text-muted my-0">{{event.location}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <loading :active.sync="isLoading"></loading>
                            <div class="row my-2" v-if="load_more_button">
                                <div class="col-md-12 text-center">
                                    <button @click="load_more()" class="btn btn-danger btn-sm">Load more</button>
                                </div>
                            </div>
                        </div>
                        <!-- sidebar -->
                        <div class="col-md-4" id="sidebar">
                                <sale-sidebar :location="search_location"></sale-sidebar>
                                <rent-sidebar :location="search_location"></rent-sidebar>
                                <job-sidebar :location="search_location"></job-sidebar>
                                <service-sidebar :location="search_location"></service-sidebar>
                                <restaurant-sidebar :location="search_location"></restaurant-sidebar>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    // Import component
    import Loading from 'vue-loading-overlay';
    // Import stylesheet
    import 'vue-loading-overlay/dist/vue-loading.css';
    // date
    import format from 'date-fns/format';
    // Sidebar
    import SaleSidebar  from '../Search/Sale.vue';
    import JobSidebar from '../Search/Job.vue';
    import RentSidebar from '../Search/Rent.vue';
    import RestaurantSidebar from '../Search/Restaurant.vue';
    import ServiceSidebar from '../Search/Service.vue';
    export default {
        props: ['location'],
        // Data
        data(){
            return{
                load_more_button : false,
                loading_placeholder:true,
                total:0,
                rating:0,
                status:true,
                empty_result:'',
                // event:[], // Restaurants Object
                events:{
                    data:[],
                    next_page_url:`/api/search/events`,
                    },
                    nextPage:2,
                    search_next_page:2,
                // filter
                filter:{
                    name:'',
                    location:this.location,
                    category:'',
                    entry_status:false,
                    fee_min:0,
                    fee_max:0,
                    from:'',
                    to:'',
                    entry_free:'',
                },
                max_date:'',
                // loading
                isLoading : false,//Lazy loading
                // lazy:false,
                result:[],
                /**
                 * Dropdown List
                 * location
                 * profession
                 * nature
                 *  */  
                places:'',
                search_location:'',
                categories:{},

            }
        },
        /**
         *  Methods
         *  */ 
        methods:{
            // load category list
            load_categories(){
                if(this.filter.category == 0){
                    this.categories = {}
                }
                if(this.filter.category.length > 0){
                axios.get(`/api/event-categories/search?name=${this.filter.category}`)
                .then(response =>{
                    this.categories = response.data.data;
                    })
                }
            },
            // star
            setRating: function(rating){
            this.rating= rating;
            },
            // Entry Free Chekc
            entry_free_status(status){
                if(status){
                    this.status = false;
                    this.search_result();
                }else{
                    this.status = true
                    this.search_result();
                }
            },
            // load places
            load_location(){
                let location = "\""+ this.filter.location+  "\"";
                if(this.filter.location ==''){
                    this.filter.location = '';
                    this.places ={};
                }else{
                    if(this.filter.location.length > 2){
                    axios.get("/api/map?query="+location)
                    .then(response=>{
                        this.locations = JSON.parse(response.data.data);
                        this.places = this.locations.suggestedLocations;
                    }) 
                    }
                }
            },
            // loading
            load_result(){
                
                if(this.location == null){
                    this.filter.location = ""
                };
                let today = new Date();
                let from = format(new Date(today), 'yyyy-MM-dd');
                this.filter.from = from;
                this.search_location = this.filter.location;
                // Slider Range
                $( function() {
                    axios.get('/api/event/list/max_fee').then(response=>{
                        const max_fee = response.data;
                        $( "#slider-range" ).slider({
                        range: true,
                        min: 0,
                        max: max_fee,
                        values: [ 0, max_fee],
                        slide: function( event, ui ) {
                            $( "#entry_fee" ).val(+ui.values[0]+"-"+ui.values[ 1 ] );
                        }
                        });
                        $( "#entry_fee" ).val( +$("#slider-range" ).slider( "values", 0 )+
                        "-"+$("#slider-range").slider( "values", 1 ) );
                    })
                });
                axios.get('/api/event/list/max_date')
                .then(response => {
                    this.filter.to = response.data;
                })
                // loading
                axios.get('/api/event/list/max_fee').then(response=>{
                    const max_fee = response.data;
                    // Get the result
                    axios.get('/api/search/events?from='
                    +this.filter.from+
                    '&to='+this.filter.to+
                    '&fee_min='+this.filter.fee_min+
                    '&fee_max='+max_fee+
                    '&entry_free='+this.filter.entry_status+
                    '&location='+this.filter.location
                    )
                    .then(response=>{ 
                        this.events = response.data.data;
                        this.loading_placeholder = false,
                        this.total = response.data.meta.total;
                        // Load more button
                        if(response.data.total == 0){
                            this.empty_result="We don't found the search item";
                        }
                        // if response it there
                        if (response.data.meta.current_page == response.data.meta.last_page) {
                            this.load_more_button = false;
                        }else{
                            this.load_more_button = true;
                        }
                    })
                })

            },
            // search result
            search_result(){
                axios.get('/api/event/list/max_date')
                .then(response => {
                    this.filter.to = response.data;
                })
                this.search_location = this.filter.location;
                // Desktop
                if(screen.width < 767){
                    $("#search_collapse").removeClass("show");
                }
                // Range
                this.isLoading = true; //Loading true
                // check
                var fee = document.getElementById("entry_fee");
                this.number = fee.value.split("-");
                this.filter.fee_min = this.number[0];
                this.filter.fee_max = this.number[1];
                //  variables
                this.nextPage = 2;
                axios.get('/api/search/events?name='+this.filter.name+
                '&location='+this.filter.location+
                '&category='+this.filter.category+
                '&from='+this.filter.from+
                '&to='+this.filter.to+
                '&entry_free='+this.filter.entry_status+
                '&fee_min='+this.filter.fee_min+
                '&fee_max='+this.filter.fee_max
                +'&page=1')
                .then((response)=>{ 
                    this.events = response.data.data;
                    this.total = response.data.meta.total;
                    this.isLoading = false; //Loading true
                    // check for empty result
                    if(response.data.meta.total == 0){
                        this.empty_result = "We don't found the search item"
                    }
                    // Check the load more button
                    if(response.data.meta.current_page == response.data.meta.last_page){
                        this.load_more_button = false; 
                    }else{
                        this.load_more_button = true; 
                    }

                })

            },
            // load more button
            
            load_more(nextPage){
             axios.get('/api/event/list/max_date')
                .then(response => {
                    this.filter.to = response.data;
                })
                    // this.loading = false;
                this.isLoading = true; //Loading true
                axios.get('/api/search/events?name='+this.filter.name+
                '&location='+this.filter.location+
                '&category='+this.filter.category+
                '&from='+this.filter.from+
                '&to='+this.filter.to+
                '&entry_free='+this.filter.entry_status+
                '&fee_min='+this.filter.fee_min+
                '&fee_max='+this.filter.fee_max+
                '&page='+this.nextPage)
                // axios.get('/api/search/events?page='+)
                .then(response=>{
                    if(response.data.meta.current_page <= response.data.meta.last_page){
                        this.nextPage = response.data.meta.current_page + 1;
                        this.isLoading = false; //Loading true
                        // loadmore Button
                        if(response.data.meta.current_page == response.data.meta.last_page){
                            this.load_more_button = false; 
                        }else{
                            this.load_more_button = true; 
                        }
                        /**
                         * Comments 
                         * data Distribution
                         *  */  
                        this.events = [
                            ...this.events,
                            ...response.data.data
                        ];    
                
                    }else{
                        // this.lazy = false;
                        this.isLoading = false; //Loading true
                    }
                })
            },
            // Reset the search form
            reset(){
                // Extract date
                axios.get('/api/event/list/max_date')
                .then(response => {
                    this.max_date = response.data;
                    let today = new Date();
                    let from = format(new Date(today), 'yyyy-MM-dd');
                    this.status = true;
                    // Reset
                    this.empty_result='';
                    // filter paramater
                    this.filter = {
                        name:'',
                        location:'',
                        category:'',
                        entry_status:false,
                        fee_min:0,
                        fee_max:0,
                        from:from,
                        to:this.max_date
                    };
                    // Desktop
                    if(screen.width < 767){
                        $("#search_collapse").removeClass("show");
                    }
                    this.search_location = '';
                    this.load_result();
                })
            },
            /**
             * Set Location
             *  */ 
            set_location(location,city){
                this.filter.location = location;
                this.places = {};
            },
            // categoryu
            event_category_dropdown() {
                $("#event_location_list").css("display", "none");
            },
            set_category(category){
                this.filter.category = category;
                this.categories = {}
            },

        },
        // Components
        components:{Loading,SaleSidebar,JobSidebar,RentSidebar,RestaurantSidebar,ServiceSidebar},
        /**
         * Filter
         *  */ 
        filters:{
            date(str){
                return format(new Date(str), 'EE, MMM dd, yyyy');
            }
        },
        // Mounted
        mounted(){
            this.load_result();
             axios.get('/api/event/list/max_date')
                .then(response => {
                    this.filter.to = response.data;
            })
        }
    }
</script>