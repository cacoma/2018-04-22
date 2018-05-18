<template>
<div class="container-fluid justify-content-center">
  <div class="card">
    <div class="card-header">Inserção em massa de {{Slug}}</div>
    <div class="card-body">
      <div class="container-fluid">
        <b-card bg-variant="light">
          <b-alert variant="danger" dismissible :show="this.form.errors.any()" @dismissed="showDismissibleAlert=false">
            <div v-for="(value, key, index) in this.form.errors.errors">
              {{ index + 1 }}. {{ this.racaz.columnName(key) }}: {{ value[0] }}
            </div>
          </b-alert>
          <b-form @submit="evaluation" @reset="onReset" v-if="this.show" @input="form.errors.clear($event.target.name)">
            <b-col>
              <b-form-group horizontal breakpoint="sm" :label-cols="2" :label="Slug" label-size="lg" label-class="font-weight-bold pt-0" class="mb-0">
                <!--               <b-form-group horizontal breakpoint="sm" class="mb-0">       -->

                <div v-for="(value, key, index) in form">
                  <b-form-group v-bind:id="key + 'label'" v-bind:label="this.racaz.columnName(key)" v-bind:label-for="key" v-if="key != 'errors' && key != 'modal' && key != 'created_at' && key != 'updated_at' && key != 'timestamp'">
                    <b-form-input v-if="key == 'symbol'" v-mask="'AAAA#.AA'" :key="key" :value="value" :ref="key" v-on:input="updateData(key)" v-bind:id="value" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira esta informação.')">
                    </b-form-input>
                    <p class="text-danger" v-if="form.errors.has(key)" v-text="form.errors.get(key)">
                    </p>
                  </b-form-group>
                </div>
              </b-form-group>
            </b-col>
            <b-row align-h="end">
              <b-col md="3" offset-md="1">
                <b-button type="submit" variant="success" :disabled="this.metaData == {}">Buscar</b-button>
                <b-button type="reset" variant="danger">Limpar</b-button>
              </b-col>
            </b-row>
          </b-form>
          <br>
          <b-container fluid v-if="showTable">
            <!-- <b-button variant="success" @click="teste()">Inserir</b-button> -->
            <b-list-group v-if="this.metaData">
              <b-list-group-item class="d-flex justify-content-between align-items-center" v-for="(key, value) in this.metaData" :key="key">
                {{value}} : {{ key }}
              </b-list-group-item>
            </b-list-group>
            <br>
            <!-- <index :items=""></index> -->

            <!-- User Interface controls -->
            <b-row>
              <b-col md="6" class="my-1">
                <b-form-group horizontal label="Filtro">
                  <b-input-group>
                    <b-form-input v-model="filter" placeholder="Digite para pesquisar" />
                    <b-input-group-append>
                      <b-btn :disabled="!filter" @click="filter = ''">Limpar</b-btn>
                    </b-input-group-append>
                  </b-input-group>
                </b-form-group>
              </b-col>
              <b-col class="my-1">
                <b-form-group horizontal label="Registros por pagina">
                  <b-col class="my-1">
                    <b-form-group horizontal class="mb-0">
                      <b-form-select :options="pageOptions" v-model="perPage" />
                    </b-form-group>
                  </b-col>
                </b-form-group>
              </b-col>
            </b-row>
            <!-- Main table element -->
            <b-table show-empty stacked="md" :items="items" :fields="fields" :current-page="currentPage" :per-page="perPage" :filter="filter" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" @filtered="onFiltered">
              <!-- <template slot="name" slot-scope="row">{{row.value.first}} {{row.value.last}}</template>
              <template slot="isActive" slot-scope="row">{{row.value?'Yes :)':'No :('}}</template> -->
              <template slot="actions" slot-scope="row">
      <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
      <b-button size="sm" @click.stop="info(row.item, row.index, $event.target)" class="mr-1">
        Info modal
      </b-button>
      <b-button size="sm" @click.stop="row.toggleDetails">
        {{ row.detailsShowing ? 'Esconder' : 'Mostrar' }} detalhes
      </b-button>
    </template>
              <template slot="row-details" slot-scope="row">
      <b-card>
        <ul>
          <li v-for="(value, key) in row.item" :key="key" v-if="key != '_showDetails'">{{ key }}: {{ value}}</li>
        </ul>
      </b-card>
    </template>
            </b-table>
            <b-col md="6" class="my-1">
              <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
            </b-col>
            <!-- Info modal -->
            <b-modal id="modalInfo" @hide="resetModal" :title="modalInfo.title" ok-only>
              <pre>{{ modalInfo.content }}</pre>
            </b-modal>
          </b-container>
          <!-- <b-button variant="success" @click="teste()">Inserir</b-button> -->
        </b-card>
      </div>
    </div>
  </div>
</div>
</template>
<script>
import {
  Errors,
  Form,
  Modal
} from './../../javascript/classes.js';

const items = [];

export default {
  data() {
    return {
      form: new Form({
        symbol: '',
      }),
      show: true,
      slug: racaz.slug,
      Slug: racaz.columnName(racaz.pathArray[1]),
      pathArray: racaz.pathArray,
      metaData: {},
      timeSeriesDaily: {},
      apikey: aplhavantage_apikey, //process.env.MIX_APLHAVANTAGE_APIKEY//"{{ env('APLHAVANTAGE_APIKEY') }}",
      items: items,
      fields: [],
      currentPage: 1,
      perPage: 20,
      totalRows: items.length,
      pageOptions: [20, 30, 50],
      sortBy: null,
      sortDesc: false,
      filter: null,
      modalInfo: {
        title: '',
        content: ''
      },
      objectLength: 0,
      showTable: false,
      symbolExists: 0
    }
  },
  computed: {
    sortOptions() {
      // Create an options list from our fields
      return this.fields
        .filter(f => f.sortable)
        .map(f => {
          return {
            text: f.label,
            value: f.key
          }
        })
    }
  },
  methods: {
    info(item, index, button) {
      this.modalInfo.title = `Row index: ${index}`
      this.modalInfo.content = JSON.stringify(item, null, 2)
      this.$root.$emit('bv::show::modal', 'modalInfo', button)
    },
    resetModal() {
      this.modalInfo.title = ''
      this.modalInfo.content = ''
    },
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },
    updateData(key) {
      Vue.set(this.form, [key], this.$refs[key][0].localValue);
    },
    // onSubmit(evt) {
    //   evt.preventDefault();
    //   this.form.post('/' + this.pathArray[1] + '/store')
    //     .then(data => {
    //       console.log('promise Success ');
    //       console.log(data);
    //     })
    //     .catch(errors => {
    //       console.log('promise Fail ');
    //       console.log(errors);
    //     });
    // },
    onReset(evt) {
      evt.preventDefault();
      this.form.reset();
      /* Reset our form values */
      /* Trick to reset/clear native browser form validation state */
      this.show = false;
      this.symbolExists = 0;
      this.$nextTick(() => {
        this.show = true;
        this.showTable = false;
      });
    },
    evaluation(evt) {
      evt.preventDefault();
      if (this.form.symbol == '') {
        flash('Favor inserir o código da ação.|info')
      } else {
        axios.get("api/stocks/symbol/" + this.form.symbol)
          .then(response => {
            this.symbolExists = response.data;
            if (this.symbolExists != 1) {
              flash('Código inexistente no sistema.|info');
              //this.onReset();
            } else {
              var axiosCrossDomain = axios;
              // var apikey = '{{ env('NAME') }}';
              var url = "https://www.alphavantage.co/query?function=TIME_SERIES_DAILY&symbol=" + this.form.symbol + "&apikey=" + this.apikey;
              console.log(url);
              delete axiosCrossDomain.defaults.headers.common["X-CSRF-TOKEN"];
              axiosCrossDomain(url, {
                  method: "GET",
                  //             params: {
                  //                 ...
                  //             }

                })
                .then(response => {
                  console.log(response);
                  console.log(response.data["Meta Data"]);
                  console.log(response.data["Time Series (Daily)"]);
                  //Vue.set(this.metaData, response.data);
                  //response.data["Meta Data"].forEach(value =>
                  console.log(Object.entries(response.data["Meta Data"]));
                  console.log(Object.entries(response.data["Time Series (Daily)"]));
                  Object.keys(response.data["Time Series (Daily)"]).forEach((key, i) => {
                    //Vue.set(this.timeSeriesDaily, "data", key);
                    Vue.set(this.timeSeriesDaily, i, Object.assign({}, {
                      "data": key
                    }, response.data["Time Series (Daily)"][key]));
                    //this.timeSeriesDaily = Object.assign({}, this.timeSeriesDaily, response.data["Time Series (Daily)"][key])
                    // response.data["Time Series (Daily)"][key][1].forEach(key2 => {
                    //   Vue.set(this.timeSeriesDaily, key2, response.data["Time Series (Daily)"][key][key2])
                    // })
                  });
                  this.fields = racaz.fieldsFiller(Object.keys(this.timeSeriesDaily[0]));
                  this.fields.push({
                    key: 'actions',
                    label: 'Opções',
                    formatter: (value, key, item) => {
                      return `/${racaz.slug}/${item.id}`;
                    }
                  });
                  this.items = Object.values(this.timeSeriesDaily);
                  this.showTable = true;
                  //this.timeSeriesDaily = JSON.parse(response.data["Time Series (Daily)"]);
                  //this.objectLength = Object.keys(this.timeSeriesDaily).length;
                  Object.keys(response.data["Meta Data"]).forEach(key => {
                    Vue.set(this.metaData, key, response.data["Meta Data"][key]);
                  });
                  Vue.set(this.metaData, "6. Tamanho", Object.keys(this.timeSeriesDaily).length);
                })
                .catch(function(error) {
                  console.log(error);
                });
            }
          })
          .catch(error => {
            console.log(error);
          });
      }

    }

  }
}
</script>
