<template>
    <div style="min-height:80vh">
        <div id="restaurant">
            <div v-if="!loading">
                <loading :active.sync="isLoading"></loading>
            </div>
            <div v-else>
                <div  class="container py-1">
                    <div class="row">
                            <div class="col-md-8 col-sm-12">
                                <!-- basic -->
                                <div class="card">
                                    <div class="row">
                                        <!-- Banner -->
                                        <div class="col-md-5">
                                            <div class="banner" v-bind:style='{ backgroundImage: `url(/storage/Job/Banner/${job.banner})`}'>
                                            <div class="overlay">
                                                <div class="d-flex flex-row job">
                                                    <a target="_blank" v-if="job.facebook" :href="job.facebook" class="m-1"><i class="fab fa-facebook-square fa-1x btn-primary btn"></i></a>
                                                    <a target="_blank" v-if="job.instagram" :href="job.instagram" class="m-1"><i class="fab fa-instagram-square fa-1x btn-danger btn"></i></a>
                                                    <a target="_blank" v-if="job.website" :href="job.website" class="m-1"><i class="fab fa-internet-explorer  btn-secondary btn"></i></a>
                                                </div>
                                                <h5 class="font-weight-bold position-absolute btn btn-danger text-white btn-flat" v-if="job.types ==1">{{job.organization}}</h5>
                                                <h6 class="font-weight-bold position-absolute btn text-white btn-danger btn-flat">{{job.title}}</h6>
                                            </div>
                                            </div>                                
                                        </div>
                                        <div class="col-sm-7" v-if="job.types == 1">
                                            <div class="row p-2">
                                                <div class="col-md-4 col-6">
                                                    <h6 class="text-dark">Salary</h6>
                                                </div>
                                                <div class="col-md-8 col-6">
                                                    <h6 v-if="job.salary > 0" class="text-muted text-left">: Rs <span class="font-weight-bold">{{job.salary}} /-</span></h6>
                                                    <h6 v-else class="text-muted text-left">:Not Disclosed</h6>
                                                </div>
                                            </div>
                                            <div class="row p-2" >
                                                <div class="col-md-4 col-6">
                                                    <h6 class="text-dark">Profession </h6>
                                                </div>
                                                <div class="col-md-8 col-6">
                                                    <h6 class="text-muted text-left">: {{job.profession}}</h6>
                                                </div>
                                            </div>
                                            <div class="row p-2">
                                                <div class="col-md-4 col-6">
                                                    <h6 class="text-dark">Nature </h6>
                                                </div>
                                                <div class="col-md-8 col-6">
                                                    <h6 class="text-muted text-left">: {{job.nature}}</h6>
                                                </div>
                                            </div>
                                            <div class="row p-2">
                                                <div class="col-md-4 col-6">
                                                    <h6 class="text-dark">Experience </h6>
                                                </div>
                                                <div class="col-md-8 col-6">
                                                    <h6 class="text-muted text-left">: {{job.experience}}</h6>
                                                </div>
                                            </div>
                                            <div class="row p-2" v-if="job.mobile_no">
                                                <div class="col-md-4 col-6">
                                                    <h6 class="text-dark">Mobile no </h6>
                                                </div>
                                                <div class="col-md-8 col-6">
                                                    <h6 class="text-muted text-left">: {{job.mobile_no}}</h6>
                                                </div>
                                            </div>
                                            <div class="row p-2">
                                                <div class="col-md-4 col-6">
                                                    <h6 class="text-dark">Location </h6>
                                                </div>
                                                <div class="col-md-8 col-6">
                                                    <h6 class="text-muted text-left">: {{job.location}}</h6>
                                                </div>
                                            </div>
                                            <div class="row p-2">
                                                <div class="col-md-4 col-6">
                                                    <h6 class="text-dark">Address </h6>
                                                </div>
                                                <div class="col-md-8 col-6">
                                                    <h6 class="text-muted text-left">: {{job.address}}</h6>
                                                </div>
                                            </div>
                                            <div class="row p-2">
                                                <div class="col-md-4 col-6">
                                                    <h6 class="text-dark">Deadline </h6>
                                                </div>
                                                <div class="col-md-8 col-6">
                                                    <h6 class="text-muted font-weight-bold text-left">: {{job.deadline}}</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-sm-7" v-if="job.types == 2">
                                            <div class="row p-2" >
                                                <div class="col-md-4 col-6">
                                                    <h6 class="text-dark">Course Name </h6>
                                                </div>
                                                <div class="col-md-8 col-6">
                                                    <h6 class="text-muted text-left">: {{job.course_name}}</h6>
                                                </div>
                                            </div>
                                            <div class="row p-2">
                                                <div class="col-md-4 col-6">
                                                    <h6 class="text-dark">Degree </h6>
                                                </div>
                                                <div class="col-md-8 col-6">
                                                    <h6 class="text-muted text-left">: {{job.graduation}}</h6>
                                                </div>
                                            </div>
                                            <div class="row p-2">
                                                <div class="col-md-4 col-6">
                                                    <h6 class="text-dark">Duration </h6>
                                                </div>
                                                <div class="col-md-8 col-6">
                                                    <h6 class="text-muted text-left">: {{job.duration}}</h6>
                                                </div>
                                            </div>
                                            <div class="row p-2" v-if="job.mobile_no">
                                                <div class="col-md-4 col-6">
                                                    <h6 class="text-dark">Mobile No</h6>
                                                </div>
                                                <div class="col-md-8 col-6">
                                                    <h6 class="text-muted text-left">: {{job.mobile_no}}</h6>
                                                </div>
                                            </div>
                                            <div class="row p-2">
                                                <div class="col-md-4 col-6">
                                                    <h6 class="text-dark">Country </h6>
                                                </div>
                                                <div class="col-md-8 col-6">
                                                    <h6 class="text-muted text-left">: {{job.country}}</h6>
                                                </div>
                                            </div>
                                            <div class="row p-2">
                                                <div class="col-md-4 col-6">
                                                    <h6 class="text-dark">Deadline </h6>
                                                </div>
                                                <div class="col-md-8 col-6">
                                                    <h6 class="text-muted font-weight-bold text-left">: {{job.deadline}}</h6>
                                                </div>
                                            </div>
                                            <div class="row p-2" v-if="job.email">
                                                <div class="col-md-4 col-6">
                                                    <h6 class="text-dark">Email</h6>
                                                </div>
                                                <div class="col-md-8 col-6">
                                                    <h6 class="text-muted text-left">: {{job.email}}</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Documents -->
                                <Document :file="job.job_document" v-if="job.job_document.length > 0"/>
                                <!-- Info -->
                                <div class="card my-2">
                                    <div  v-if="job.description != null">
                                        <div class="row p-3" v-if="job.description.length > 250">
                                            <div class="col-md-12">
                                                <h5 class="text-dark">Description</h5>
                                                <p class="text-muted">
                                                    {{job.description}}
                                                </p>
                                            </div>
                                            <div class="col-md-12"> 
                                                <map-location :longitude="job.longitude" :latitude="job.latitude"></map-location>
                                            </div>
                                        </div>
                                        <div class="row p-3" v-else>
                                            <div class="col-md-6">
                                                <h5 class="text-dark">Description</h5>
                                                <p class="text-muted">
                                                    {{job.description}}
                                                </p>
                                            </div>
                                            <div class="col-md-6"> 
                                                <map-location :longitude="job.longitude" :latitude="job.latitude"></map-location>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row p-3" v-else>
                                            <div class="col-md-12"> 
                                                <map-location :longitude="job.longitude" :latitude="job.latitude"></map-location>
                                            </div>
                                    </div>
                                </div>
                                <!-- Question & Ansert -->
                                <job-question :job_uuid="job.id"></job-question>
                                <PopupAd></PopupAd>
                            </div>
                        <!-- Sidebar -->
                            <div class="col-md-4 col-sm-12" id="sidebar">
                                <!-- sidebar -->
                                <job-sidebar :location="location" :id="id"></job-sidebar>
                                <google-adsense></google-adsense>
                                <rent-sidebar :location="location"></rent-sidebar>
                                <sale-sidebar :location="location"></sale-sidebar>
                                <event-sidebar :location="location"></event-sidebar>
                                <restaurant-sidebar :location="location"></restaurant-sidebar>
                                <service-sidebar :location="location"></service-sidebar>
                            </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <apply :job_id="id" @close_modal="close_modal"></apply> -->
    </div>
</template>
<script>
// Import component
import Loading from 'vue-loading-overlay';
// Import stylesheet
import 'vue-loading-overlay/dist/vue-loading.css';
// Sidebars
import SaleSidebar  from '../Sidebar/Sale.vue';
import EventSidebar from '../Sidebar/event.vue';
import JobSidebar from '../Sidebar/job.vue';
import RentSidebar from '../Sidebar/rent.vue';
import RestaurantSidebar from '../Sidebar/restaurant.vue';
import ServiceSidebar from '../Sidebar/service.vue';
import Document from './Document';
import PopupAd from '../PopupAd/Job';

export default {
    props:['job_id'],
    data(){
        return{
            id:this.job_id['id'], //Rent Id
            user_id:'',
            job:{}, //job objects
            isLoading : false,//Lazy loading
            loading:false, //loading
            // status 
            is_logged:false,
            applied:false,
            applied_id:'',
            interested:false,
            interested_id:'',
            location:'',
        }
    },
    methods:{
        load_job(){
            // Job Object data
            this.isLoading = true;
            axios.get('/api/job/view/'+this.id)
            .then(response=>{

                this.job = response.data.data;
                this.isLoading = false;
                this.loading = true;
                this.location = this.job.location;
                // Check Login Status
                axios.get('/login_status').then(response => {
                    if(response.data.status == true){
                        this.is_logged = true;
                        this.user_id = response.data.user.id;
                        // Check if user
                        // Applied this Job
                        // for (let index = 0; index < this.job.job_applied.length; index++) {
                        //     if(this.job.job_applied[index].user_id == this.user_id){
                        //         this.applied = true;
                        //         this.applied_id = this.job.job_applied[index].id
                        //         break;
                        //     }
                        // }
                        // Check If user
                        // User is interested
                        for (let y = 0; y < this.job.job_interested.length; y++) {
                            if(this.job.job_interested[y].user_id == this.user_id){
                            // console.log(this.job.job_interested[y].id)
                                this.interested = true;
                                this.interested_id = this.job.job_interested[y].id
                                break;
                            }
                        }
                    }
                })
            })
        },
        // Interested
        thumbs_up(id,count){
            this.interested = true;
            this.job_interested = count +1;
            axios.post('/api/job_interest',{id:this.id},{
                headers : { Authorization : localStorage.getItem("token")}
            })
            .then(repsone=>{
            // this.interested = true;
            this.load_job();
            })
        },
        // Unliked
        thumbs_down(id,count){
            this.interested = false;
            this.job_interested = count -1;
            axios.delete('/api/job_interest/'+id,{
                headers : { Authorization : localStorage.getItem("token")}
            })
            .then(repsone=>{
                this.interested = false
            this.load_job();
            })
        },
        // open modal
        open_modal(){
            $("#job_apply").modal("show");
        },
        close_modal(){
            $("#job_apply").modal("hide");
        }
    },
    /**
     * 
     * Components
     *  */  
    components:{Loading,Document,SaleSidebar,EventSidebar,JobSidebar,RentSidebar,RestaurantSidebar,ServiceSidebar,PopupAd},
    mounted(){
        this.load_job();
    }
}
</script>