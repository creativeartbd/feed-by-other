<template>
  <vs-card no-shadow>
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-4">
      <div class="card pt-4 pb-4" style="width: 100%;">
        <div class="row">
          <div class="col-md-1">
            <div class="card-body">
              <img class="card-img-top" src="../../../assets/images/product-image/xml.png"
                   alt="XML">
            </div>
          </div>

          <div class="col-md-7">
            <div class="card-body">
              <div>
                <h5 class="card-title">XML</h5>
              </div>
              <p class="card-text">Some quick example text to build on the card title and make <br> up the bulk of the
                card's content.</p>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card-body">
              <a @click="popupActive=true" style="padding: 0" class="btn btn-link">Go somewhere</a>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
                content.</p>
            </div>
          </div>

          <div class="col-md-1" style="line-height: 85px">
            <div class="card-body">
              <i class="fa fa-angle-right arrow-sign"></i>
            </div>
          </div>
        </div>

        <div class="demo-alignment">
          <vx-popup fullscreen title="fullscreen" :active.sync="popupActive">
            <!-- Tabs -->
            <section id="tabs">
              <div class="container">
                <div>
                  <div>
                    <nav>
                      <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link" id="nav-home-tab" data-toggle="tab" @click="tab('setting')"
                           role="tab" aria-controls="nav-home" aria-selected="true" :class="{ active : activeTab == 'setting' }">
                          Settings
                        </a>
                        <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" :class="{ active : activeTab == 'mapping' }"
                           role="tab" aria-controls="nav-profile" aria-selected="false" @click="tab('mapping')">
                          Edit mapping
                        </a>
                        <a class="nav-item nav-link" id="nav-contact-tab" data-toggle="tab" @click="tab('safety')"
                           :class="{ active : activeTab == 'safety' }"
                           role="tab" aria-controls="nav-contact" aria-selected="false">
                          Safety
                        </a>
                      </div>
                    </nav>
                    <div class="tab-content py-3 px-3 px-sm-0" id="nav-tabContent">
                      <div v-if="activeTab == 'setting'"   class="tab-pane  show" id="nav-home" :class="{ active : activeTab == 'setting' }"
                           role="tabpanel" aria-labelledby="nav-home-tab">
                        <vs-input class="w-full mb-base" name="name" v-validate="'required'"
                                  label-placeholder="Name" v-model="name"></vs-input>
                        <!-- <span class="text-danger text-sm">{{ errors.first('name') }}</span> -->
                        <label>XML file url:</label>
                        <vs-input-group class="mb-base" name="name_group">
                          <template slot="prepend">
                            <div class="prepend-text bg-primary" style="background-color: #ffffff !important; padding-left: 0px;">
                              <select class="form-control" v-model="http">
                                <option selected>https</option>
                                <option>http</option>
                                <option>sftp</option>
                                <option>ftp</option>
                              </select>
                            </div>
                          </template>
                          <vs-input v-model="xml_file_url" name="xml_file_url" v-validate="'required'"/>
                          <!-- <span class="text-danger text-sm">{{ errors.first('xml_file_url') }}</span> -->
                          <template slot="append">
                            <div class="append-text btn-addon" style="background-color: #ffffff !important;">
                              <select class="form-control" name="authentication" v-validate="'required'" v-model="authentication">
                                <option value="no_authentication">No authentication</option>
                                <option value="username_password">Username & password</option>
                                <option value="x_api_key_header">Authentication header</option>
                              </select>
                            </div>
                          </template>
                        </vs-input-group>

                        <label>Encoding:</label>
                        <select  class="form-control" v-model="encoding" name="encoding" v-validate="'required'">
                          <option value="0">Automatically detect</option>
                          <option value="utf-8">UTF-8</option>
                          <option value="utf-16">UTF-16 with byte order mark</option>
                          <option value="utf-16le">UTF-16 (little endian)</option>
                          <option value="utf-16be">UTF-16 (big endian)</option>
                          <option value="utf-32">UTF-32 with byte order mark</option>
                          <option value="utf-32le">UTF-32 (little endian)</option>
                          <option value="utf-32be">UTF-32 (big endian)</option>
                          <option value="iso8859-1">ISO-8859-1</option>
                          <option value="iso8859-15">ISO-8859-15</option>
                          <option value="cp1252">Windows-1252</option>
                          <option value="cp1254">Windows-1254</option>
                        </select>
                        <!-- <span class="text-danger text-sm">{{ errors.first('encoding') }}</span> -->
                        <b-button  v-ripple.400="'rgba(255, 255, 255, 0.15)'" variant="primary" ref="loadableButton" style="margin-top: 20px" id="button-with-loading" @click="continueXml" :disabled="!validateForm"
                                   class="vs-con-loading__container" type="relief">
                          Continue
                        </b-button>
                      </div>
                      <div v-if="activeTab == 'mapping'" class="tab-pane show" id="nav-profile"
                           role="tabpanel" aria-labelledby="nav-profile-tab" :class="{ active : activeTab == 'mapping' }">
                          <div class="mt-5" v-if="projectFields.length">
                            <table class="table">
                              <thead>
                              <tr>
                                <th scope="col">Import field</th>
                                <th scope="col">Internal Channable field</th>
                                <th scope="col">Options</th>
                              </tr>
                              </thead>
                              <tbody>
                              <tr v-for="projectField in projectFields" :key="projectField.id">
                                <td>{{ projectField }}</td>
                                <td>
                                  <select class="form-control" v-if="existCustom(projectField)" name="productMappings.importField" v-model="productMappings.importField">
                                    <option value="0">- do not import -</option>
                                    <option :selected="xmlField.field_title == projectField" :value="xmlField.id_xml_field" :key="xmlField.id" v-for="xmlField in xmlFields">
                                      {{ xmlField.field_title }}
                                    </option>
                                  </select>
                                  <input class="form-control" v-if="!existCustom(projectField)" type="text" name="productMappings.importField"  :value="projectField" />
                                </td>
                                <td>
                                  <div class="row">
                                    <div class="col-md-6">
                                      <i class="fa fa-trash-o delete-button" aria-hidden="true"></i>
                                    </div>
                                    <div class="col-md-6">
                                      <input class="form-control"  type="hidden" name="productMappings.isCustom" :value="existCustom(projectField)" />
                                      <button v-if="existCustom(projectField)" class="form-control">Custom</button>
                                      <button v-if="!existCustom(projectField)" class="form-control">Custom check</button>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                              </tbody>
                            </table>
                            <vs-button ref="loadableButton" style="margin-top: 20px" id="loading-save-import"
                                       class="vs-con-loading__container" type="relief" @click="saveImport">
                              Save & import
                            </vs-button>
                        </div>
                      </div>
                      <div v-if="activeTab == 'safety'" class="tab-pane show" id="nav-contact" role="tabpanel"
                           aria-labelledby="nav-contact-tab" :class="{ active : activeTab == 'safety' }">
                        <div v-if="projectFields.length">
                          <label>Action on difference:</label>
                          <select  class="form-control" v-model="no_action" name="no_action">
                            <option value="no_action">No action</option>
                            <option value="send_notification">Send notification</option>
                            <option value="block_import">Block and send notification</option>
                          </select>

                          <div class="mt-5">
                            <div class="row">
                              <div class="col-md-6">
                                <label> Auto update product titles on future updates:</label>
                                <label class="mt-3"> Auto update product description on future updates:</label>
                                <label class="mt-3">Auto update future product images:</label>
                              </div>
                              <div class="col-md-6">
                                <vs-switch vs-icon-on="check" color="success" v-model="title" vs-value="summer">
                                  <span slot="off"></span>
                                </vs-switch>
                                <vs-switch vs-icon-on="check" color="success" v-model="description" vs-value="summer"  class="mt-3">
                                  <span slot="off"></span>
                                </vs-switch>
                                <vs-switch vs-icon-on="check" color="success" v-model="image" vs-value="summer" class="mt-3">
                                  <span slot="off"></span>
                                </vs-switch>
                              </div>
                            </div>
                          </div>


                          <vs-button color="primary" id="loading-save" style="margin-top: 20px" @click="save">Save</vs-button>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </section>
            <!-- ./Tabs -->
          </vx-popup>
        </div>

      </div>
    </div>

    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-4">
      <div class="card pt-4 pb-4" style="width: 100%;">
        <div class="row">
          <div class="col-md-1">
            <div class="card-body">
              <img class="card-img-top" src="../../../assets/images/product-image/csv.png"
                   alt="XML">
            </div>
          </div>

          <div class="col-md-7">
            <div class="card-body">
              <div>
                <h5 class="card-title">CSV</h5>
              </div>
              <p class="card-text">Some quick example text to build on the card title and make <br> up the bulk of the
                card's content.</p>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card-body">
              <a href="#" style="padding: 0" class="btn btn-link">Go somewhere</a>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
                content.</p>
            </div>
          </div>

          <div class="col-md-1" style="line-height: 85px">
            <div class="card-body">
              <i class="fa fa-angle-right arrow-sign"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-4">
      <div class="card pt-4 pb-4" style="width: 100%;">
        <div class="row">
          <div class="col-md-1">
            <div class="card-body">
              <img class="card-img-top" src="../../../assets/images/product-image/ps.png"
                   alt="XML">
            </div>
          </div>

          <div class="col-md-7">
            <div class="card-body">
              <div>
                <h5 class="card-title">Prestashop</h5>
              </div>
              <p class="card-text">Some quick example text to build on the card title and make <br> up the bulk of the
                card's content.</p>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card-body">
              <a href="#" style="padding: 0" class="btn btn-link">Go somewhere</a>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
                content.</p>
            </div>
          </div>

          <div class="col-md-1" style="line-height: 85px">
            <div class="card-body">
              <i class="fa fa-angle-right arrow-sign"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-4">
      <div class="card pt-4 pb-4" style="width: 100%;">
        <div class="row">
          <div class="col-md-1">
            <div class="card-body">
              <img class="card-img-top" src="../../../assets/images/product-image/mg.png"
                   alt="XML">
            </div>
          </div>

          <div class="col-md-7">
            <div class="card-body">
              <div>
                <h5 class="card-title">Magento</h5>
              </div>
              <p class="card-text">Some quick example text to build on the card title and make <br> up the bulk of the
                card's content.</p>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card-body">
              <a href="#" style="padding: 0" class="btn btn-link">Go somewhere</a>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
                content.</p>
            </div>
          </div>

          <div class="col-md-1" style="line-height: 85px">
            <div class="card-body">
              <i class="fa fa-angle-right arrow-sign"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-4">
      <div class="card pt-4 pb-4" style="width: 100%;">
        <div class="row">
          <div class="col-md-1">
            <div class="card-body">
              <img class="card-img-top" src="../../../assets/images/product-image/woo.jpeg"
                   alt="XML">
            </div>
          </div>

          <div class="col-md-7">
            <div class="card-body">
              <div>
                <h5 class="card-title">WooCommerce</h5>
              </div>
              <p class="card-text">Some quick example text to build on the card title and make <br> up the bulk of the
                card's content.</p>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card-body">
              <a href="#" style="padding: 0" class="btn btn-link">Go somewhere</a>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
                content.</p>
            </div>
          </div>

          <div class="col-md-1" style="line-height: 85px">
            <div class="card-body">
              <i class="fa fa-angle-right arrow-sign"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-4">
      <div class="card pt-4 pb-4" style="width: 100%;">
        <div class="row">
          <div class="col-md-1">
            <div class="card-body">
              <img class="card-img-top" src="../../../assets/images/product-image/big.png"
                   alt="XML">
            </div>
          </div>

          <div class="col-md-7">
            <div class="card-body">
              <div>
                <h5 class="card-title">BigCommerce</h5>
              </div>
              <p class="card-text">Some quick example text to build on the card title and make <br> up the bulk of the
                card's content.</p>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card-body">
              <a href="#" style="padding: 0" class="btn btn-link">Go somewhere</a>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
                content.</p>
            </div>
          </div>

          <div class="col-md-1" style="line-height: 85px">
            <div class="card-body">
              <i class="fa fa-angle-right arrow-sign"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-12 col-md-12 col-lg-12 mb-4">
      <div class="card pt-4 pb-4" style="width: 100%;">
        <div class="row">
          <div class="col-md-1">
            <div class="card-body">
              <img class="card-img-top" src="../../../assets/images/product-image/shopify.jpg"
                   alt="XML">
            </div>
          </div>

          <div class="col-md-7">
            <div class="card-body">
              <div>
                <h5 class="card-title">Shopify</h5>
              </div>
              <p class="card-text">Some quick example text to build on the card title and make <br> up the bulk of the
                card's content.</p>
            </div>
          </div>

          <div class="col-md-3">
            <div class="card-body">
              <a href="#" style="padding: 0" class="btn btn-link">Go somewhere</a>
              <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's
                content.</p>
            </div>
          </div>

          <div class="col-md-1" style="line-height: 85px">
            <div class="card-body">
              <i class="fa fa-angle-right arrow-sign"></i>
            </div>
          </div>
        </div>
      </div>
    </div>
  </vs-card>
</template>

<script>

import axios from 'axios';
import {
  BCard, BCardTitle, BCardSubTitle, BCardBody, BCollapse, BButton
} from 'bootstrap-vue'

export default {
  components: {
    BButton
  },
  data () {
    return {
      old_password: '',
      new_password: '',
      confirm_new_password: '',
      popupActive: false,
      name: '',
      xml_file_url: '',
      projectFields: {},
      xmlFields: {},
      http: 'http',
      authentication: 'no_authentication',
      encoding: 0,
      activeTab: 'setting',
      no_action: 'no_action',
      title: true,
      description: true,
      image: true,
      productMappings: {
        importField: '',
        internalChannableField: '',
        isCustom: false
      }
    }
  },
  computed: {
    activeUserInfo () {
      return this.$store.state.AppActiveUser
    },

    validateForm () {
      return /*!this.errors.any() && */this.name !== '' && this.xml_file_url !== '' && this.http !== '' && this.authentication !== '' && this.encoding !== ''
    }
  },

  methods:{
    continueXml() {

      this.$vs.loading({
        background: this.backgroundLoading,
        color: this.colorLoading,
        container: '#button-with-loading',
        scale: 0.45
      })

      if (this.validateForm) {
        axios.post('xml-import', {name:this.name, xml_file_url:this.xml_file_url}).then(response => {
          this.projectFields = response.data
          this.activeTab = 'mapping';
          this.$vs.loading.close('#button-with-loading > .con-vs-loading')
        }).catch(error => {
          this.$vs.loading.close('#button-with-loading > .con-vs-loading')
        });
      }
    },

    tab(tab) {
      this.activeTab = tab;
    },

    saveImport() {
      console.log(this.productMappings)
      this.activeTab = 'safety';
    },

    existCustom(value) {
      var obj = this.xmlFields.filter(function(elem){
        if(elem.field_title == value) return elem.field_title;
      })
      if(obj.length > 0) {
        return true;
      } else {
        return false;
      }
    },

    getXmlFields() {
      axios.get('xml-fields', {name:this.name, xml_file_url:this.xml_file_url}).then(response => {
        this.xmlFields = response.data
      }).catch(error => {

      });
    },

    save() {
      this.$vs.loading({
        background: this.backgroundLoading,
        color: this.colorLoading,
        container: '#loading-save',
        scale: 0.45
      })


      axios.post('save-import-data',
        {
          name: this.name,
          http: this.http,
          authentication: this.authentication,
          encoding: this.encoding,
          xml_file_url: this.xml_file_url,
          no_action: this.no_action,
          projectFields: this.projectFields,
          import_type: 'XML',
          id_project: 1,
          title: this.title,
          image: this.image,
          description: this.description,
        }
        ).then(response => {
        // this.xmlFields = response.data
        this.$vs.loading.close('#loading-save > .con-vs-loading')
      }).catch(error => {
        this.$vs.loading.close('#loading-save > .con-vs-loading')
      });
    }
  },

  mounted () {
    this.getXmlFields();
  }
}
</script>

<style>
.card-img-top {
  width: 40px;
  height: 40px;
}

.arrow-sign {
  font-size: 25px;
}

.form-control {
  height: 42px;
}

.delete-button{
  font-size: 24px;
  color: red;
}

/* Tabs*/
section {
  padding: 60px 0;
}

section .section-title {
  text-align: center;
  color: #007b5e;
  margin-bottom: 50px;
  text-transform: uppercase;
}

.nav-item{
  color: #303A73 !important;
  cursor: pointer;
}


#tabs .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
  color: #f3f3f3;
  background-color: transparent;
  border-color: transparent transparent #f3f3f3;
  border-bottom: 4px solid !important;
  font-size: 20px;
  font-weight: bold;
}
#tabs .nav-tabs .nav-link {
  border: 1px solid transparent;
  border-top-left-radius: .25rem;
  border-top-right-radius: .25rem;
  color: #eee;
  font-size: 20px;
}
</style>
