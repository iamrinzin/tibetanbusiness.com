<template>
    <div>
        <div class="modal fade add_edit_label" id="service_add_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                <div class="modal-header bg-gradient-danger">
                    <h5 class="modal-title" id="exampleModalLongTitle">Add New Service</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <form @submit.prevent="add_service()"  data-vv-scope="service_validate_add_form">
                        <div class="modal-body">
                            <div class="container-fluid">
                                <div v-if="bannerPreview" class="col-md-12" style="background-size: cover;height:220px;background-position: center;" v-bind:style='{ backgroundImage: `url(${bannerPreview})`}'>
                                </div>
                                <div class="mt-1">
                                    <vue-progress-bar></vue-progress-bar>
                                </div>
                                <div class="row">
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Name<span class="text-danger p-1">*</span></label>
                                            <input type="text" v-validate="'required|min:2|max:40'" v-model="service.name" name="name" class="form-control" id="name" aria-describedby="emailHelp" placeholder="name">
                                            <div class="valid-feedback"></div>
                                            <div v-if="errors.has('service_validate_add_form.name')" class="invalid-feedback">
                                                <span v-for="error in errors.collect('service_validate_add_form.name')">{{ error }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="type">Type<span class="text-danger p-1">*</span></label>
                                            <select v-validate="'required'" v-model="service.type" name="type" class="form-control" id="type">
                                                <option value="" selected disabled>Service Type</option>
                                                <option v-for="category in categories" :value="category.name">{{category.name}}</option>
                                            </select>
                                            <div class="valid-feedback"></div>
                                            <div v-if="errors.has('service_validate_add_form.type')" class="invalid-feedback">
                                                <span v-for="error in errors.collect('service_validate_add_form.type')">{{ error }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="location">Location<span class="text-danger p-1">*</span></label>
                                                 <input type="text" autocomplete="off" name="location" v-validate="'required'" @keyup="load_location()"  v-model="service.location" class="rounded form-control "  placeholder="Location" aria-label="Location">
                                                <ul class="w-100 pl-0" style="position: absolute;z-index:100">
                                                    <li style="list-style:none;cursor:pointer"  class="p-2 text-dark border-bottom bg-light" v-for="(place,index) in places" @click="set_location(place.placeName,place.placeAddress,index)" v-if="index <= 5">
                                                        <span class="font-weight-bold text-dark" style="font-size:13px">{{place.placeName}}</span>
                                                        <span class="d-block text-muted" style="font-size:12px">{{place.placeAddress}}</span>
                                                    </li>
                                                </ul>
                                            <div class="valid-feedback"></div>
                                            <div v-if="errors.has('service_validate_add_form.location')" class="invalid-feedback">
                                                <span v-for="error in errors.collect('service_validate_add_form.location')">{{ error }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="opening_hour">Opening hour<span class="text-danger p-1">*</span></label>
                                            <input type="time" v-validate="'required'" v-model="service.opening_hour" name="opening_hour" class="form-control" id="opening_hour" aria-describedby="emailHelp" placeholder="opening hour">
                                            <div class="valid-feedback"></div>
                                            <div v-if="errors.has('service_validate_add_form.opening_hour')" class="invalid-feedback">
                                                <span v-for="error in errors.collect('service_validate_add_form.opening_hour')">{{ error }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="closing_hour">closing hour<span class="text-danger p-1">*</span></label>
                                            <input type="time" v-validate="'required'" v-model="service.closing_hour" name="closing_hour" class="form-control" id="closing_hour" aria-describedby="emailHelp" placeholder="closing hour">
                                            <div class="valid-feedback"></div>
                                            <div v-if="errors.has('service_validate_add_form.closing_hour')" class="invalid-feedback">
                                                <span v-for="error in errors.collect('service_validate_add_form.closing_hour')">{{ error }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="deadline">Mobile no<span class="text-danger p-1">*</span></label>
                                            <input type="text" v-validate="'required|numeric|max:10|min:10'" v-model="service.mobile_no" name="mobile_no" class="form-control" id="mobile_no" aria-describedby="emailHelp" placeholder="Mobile No">
                                            <div class="valid-feedback"></div>
                                            <div v-if="errors.has('service_validate_add_form.mobile_no')" class="invalid-feedback">
                                                <span v-for="error in errors.collect('service_validate_add_form.mobile_no')">{{ error }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 col-sm-6">
                                        <div class="form-group">
                                            <label for="banner">Banner Image <span class="text-danger p-1">*</span><span class="small text-danger">(Less than 1MB)</span></label>
                                            <div class="custom-file">
                                                <input type="file" v-validate="'required|image|ext:jpeg,jpg,png,gif|size:1000'" name="banner" @change="avatar" class="custom-file-input" id="banner" aria-describedby="emailHelp" placeholder="Website Address">
                                                <label class="custom-file-label" for="banner">Choose file</label>
                                                <div class="valid-feedback"></div>
                                                <div v-if="errors.has('service_validate_add_form.banner')" class="invalid-feedback">
                                                    <span v-for="error in errors.collect('service_validate_add_form.banner')">{{ error }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                    <button class="btn btn-secondary" type="button" data-toggle="collapse" data-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">Optional Fields</button> <small class="text-muted font-weight-bolder">You can add the additional fields</small>
                                    </div>
                                    <div class="collapse" id="collapseExample">
                                        <div class="row p-1">
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-group">
                                                    <label for="deadline">Email<small class="text-success p-1">(Optional)</small></label>
                                                    <input type="text" v-validate="'min:10|max:40'" v-model="service.email" name="email" class="form-control" id="email" aria-describedby="emailHelp" placeholder="Email">
                                                    <div class="valid-feedback"></div>
                                                    <div v-if="errors.has('service_validate_add_form.email')" class="invalid-feedback">
                                                        <span v-for="error in errors.collect('service_validate_add_form.email')">{{ error }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-group">
                                                    <label for="instagram">Instagram <small class="text-success">(optional)</small></label>
                                                    <input type="text" v-validate="'max:50|url'" v-model="service.instagram" name="instagram" class="form-control" id="instagram" aria-describedby="emailHelp" placeholder="Instagram">
                                                    <div class="valid-feedback"></div>
                                                    <div v-if="errors.has('service_validate_add_form.instagram')" class="invalid-feedback">
                                                        <span v-for="error in errors.collect('service_validate_add_form.instagram')">{{ error }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-group">
                                                    <label for="facebook">Facebook <small class="text-success">(optional)</small></label>
                                                    <input type="text" v-validate="'max:50|url'" v-model="service.facebook" name="facebook" class="form-control" id="facebook" aria-describedby="emailHelp" placeholder="Facebook">
                                                    <div class="valid-feedback"></div>
                                                    <div v-if="errors.has('service_validate_add_form.facebook')" class="invalid-feedback">
                                                        <span v-for="error in errors.collect('service_validate_add_form.facebook')">{{ error }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-group">
                                                    <label for="website">Website <small class="text-success">(optional)</small></label>
                                                    <input type="text" v-validate="'max:50|url'" v-model="service.website" name="website" class="form-control" id="website" aria-describedby="emailHelp" placeholder="Facebook">
                                                    <div class="valid-feedback"></div>
                                                    <div v-if="errors.has('service_validate_add_form.website')" class="invalid-feedback">
                                                        <span v-for="error in errors.collect('service_validate_add_form.website')">{{ error }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-group">
                                                    <label for="deadline">Address<small class="text-success p-1">(Optional)</small></label>
                                                    <textarea rows="5" cols="50" v-validate="'min:2|max:255'" v-model="service.address" name="address" class="form-control" id="address" aria-describedby="emailHelp" placeholder="Address"></textarea>
                                                    <div class="valid-feedback"></div>
                                                    <div v-if="errors.has('service_validate_add_form.address')" class="invalid-feedback">
                                                        <span v-for="error in errors.collect('service_validate_add_form.address')">{{ error }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-sm-6">
                                                <div class="form-group">
                                                    <label for="description">Service Description <small class="text-success p-1">(Optional)</small></label>
                                                    <textarea rows="5" cols="50" v-validate="'max:5000'" v-model="service.description" name="description" class="form-control" id="description" aria-describedby="emailHelp" placeholder="Descriptions" ></textarea>
                                                    <div class="valid-feedback"></div>
                                                    <div v-if="errors.has('service_validate_add_form.description')" class="invalid-feedback">
                                                        <span v-for="error in errors.collect('service_validate_add_form.description')">{{ error }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer d-flex justify-content-center">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-danger btn-md" placeholder="Write your comment">Create</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { Validator } from 'vee-validate';

export default {
    props:['load_service'],
    data(){
        return{
            service:{
                'longitude':20.96,
                'latitude':78.78
            },
            id:'',
            bannerPreview:'',
            places:{},
            categories:{},
        }
    },
    methods:{
        // load places
        load_location(){
            let location = "\""+ this.service.location+  "\"";
            if(this.service.location ==''){
                this.service.location = '';
                this.places ={};
            }else{
                if(this.service.location.length > 2){
                // axios.get('https://api.mapbox.com/geocoding/v5/mapbox.places/'+this.service.location+'.json?access_token=pk.eyJ1IjoicmluemluMjAyMCIsImEiOiJja2szcm1iN3ExZHRiMm9wY3Z5OWx6dnZ4In0.4TuimSiBj9l5OKTybvcrAQ&cachebuster=1611047895214&autocomplete=true&types=place%2Clocality&country=in&worldview=in&limit=8')
                axios.get("/api/map?query="+location)
                .then(response=>{
                    // this.places =  response.data.features;
                    this.locations = JSON.parse(response.data.data);
                    this.places = this.locations.suggestedLocations;
                }) 
                }
            }
        },
        /**
            * Set Location
            *  */ 
        set_location(location,city,index){
            this.service.location = location;
            this.service.address = this.places[index].placeAddress;
            //longitude
            this.service.longitude = this.places[index].longitude;
            // latitude
            this.service.latitude = this.places[index].latitude;
            // Reset location
            this.places = {};
        },
        /**
         * Banner Image 
         * File
         *  */ 
        avatar(event){
            let fileReader = new FileReader();
            if(event.target.files[0].size < 1000000){
                fileReader.onload = (event) =>{
                    this.bannerPreview = event.target.result
                    this.service.banner = event.target.result
                }
                
            }else{
                alert("Image size should be less than 1MB")
            }
            // base64 data
            fileReader.readAsDataURL(event.target.files[0]);
        },

       add_service(){
               this.$validator.validateAll('service_validate_add_form').then((result) => {
                    if(result){
                    this.$Progress.start()
                     axios.post('/api/service',this.service,{
                        headers : { Authorization : localStorage.getItem("token")}
                        })
                        .then(response=>{
                            this.id = response.data.id;
                            // closing modal
                                $("#service_add_modal").modal("hide");  
                                //  Flash Message  
                                toast.fire({
                                    icon:'success',
                                    title:'Updated',
                                });
                                this.operation ={
                                    monday : true,
                                    tuesday : true,
                                    wednesday : true,
                                    thursday : true,
                                    friday : true,
                                    saturday : true,
                                    sunday : false,
                                    service_basic_info_id : this.id
                                }
                                this.$emit('load_service');
                                axios.post('/api/service_working_day',this.operation,{
                                headers : { Authorization : localStorage.getItem("token")}
                                })
                                .then(response=>{
                                })
                    this.$Progress.finish()
                        })
                    }
               })
        }
    },
    mounted(){
        axios.get('/api/service-categories')
        .then(response=>{
            this.categories = response.data;
        })

    }
}
</script>