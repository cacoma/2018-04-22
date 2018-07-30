<!-- <template>
  <b-table striped hover :busy.sync="isBusy" :items="items" :fields="fields"></b-table>
</template>
<script>
export default {
  props: ['data'],
  data () {
    return {
      items: [],
      fields: [],
      isBusy: true
    }
  },
  created: function (){
    this.fields = racaz.fieldsFillerInvests(Object.keys(this.data[0]));
          this.fields.push({
            key: 'actions',
            label: 'Opções',
            formatter: (value, key, item) => {
              //return `/${racaz.slug}/${response.data.id}`;
            }
          });
          this.items = this.data;
          this.totalRows = this.data.length;
          // Here we could override the busy state, setting isBusy to false
          this.isBusy = false;
  }
}
</script> -->
<template>
  <b-card header="Operacoes" header-tag="header" title="" v-if="loaded === true">
    <p class="card-text">
      <!-- <b-row>
        <b-col md="6" class="my-1">
          <b-form-group horizontal label="Filtro" class="mb-0">
            <b-input-group>
              <b-form-input v-model="filter" placeholder="Pesquisar" />
              <b-input-group-append>
                <b-btn :disabled="!filter" @click="filter = ''">Limpar</b-btn>
              </b-input-group-append>
            </b-input-group>
          </b-form-group>
        </b-col>
        <b-col md="6" class="my-1">
          <b-form-group horizontal label="Itens por pagina" class="mb-0">
            <b-form-select :options="pageOptions" v-model="perPage" />
          </b-form-group>
        </b-col>
      </b-row> -->

      <!-- Main table element -->
      <b-table ref="table" id="table" show-empty responsive stacked="lg" :busy.sync="isBusy" :items="items" :fields="fields" :current-page="currentPage" :per-page="perPage" :filter="filter" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" @filtered="onFiltered">
        <template slot="empty">
          Nenhuma operação encontrada.
        </template>
        <template slot="emptyfiltered">
          Nenhuma operação encontrada com este filtro.
        </template>
        <template slot="actions" slot-scope="row">
<b-row>
  <b-col>
        <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
        <b-button size="sm" variant="secondary" @click.stop="toggleInfo(row.item, row.index)">
<!--           {{ row.detailsShowing ? 'Fechar' : 'Abrir' }} detalhes -->
          <span v-html="octicons.search.toSVG()"></span>
        </b-button>
        <b-button size="sm" variant="primary" @click.stop="alert('todo')">
          <span v-html="octicons.pencil.toSVG()"></span>
        </b-button>
        <b-button size="sm" variant="danger" @click.stop="alert('todo')">
          <span v-html="octicons.trashcan.toSVG()"></span>
        </b-button>
          </b-col>
          </b-row>
      </template>
        <template slot="row-details" slot-scope="row">
        <b-card>
<!--  funcao para nao mostrar o que sao variaveis          -->
          <ul v-for="(value, key) in row.item">
<!--             <li v-if="key !== '_cellVariants' && key !== '_showDetails' && key !== 'index' && key !== 'operation'" :key="key">{{ this.racaz.columnNameInvest(key) }}: {{ this.racaz.formtt([key,value]) }}</li> -->
            <li v-if="key !== '_cellVariants' && key !== '_showDetails' && key !== 'index' && key !== 'operation'" :key="key">{{ key }}: {{ value }}</li>
          </ul>
        </b-card>
      </template>
      </b-table>
      <!-- <b-row>
        <b-col md="12" class="my-1 mx-auto">
          <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
        </b-col>
      </b-row> -->
    </p>
  </b-card>
</div>
</template>
<script>
let items = {};
  var octicons = require("octicons");
 export default {
  props: ['inv_id'],
  data() {
    return {
      keys: [],
      fields: [],
      currentPage: 1,
      perPage: 5,
      totalRows: 0,
      pageOptions: [5, 10, 15],
      sortBy: null,
      sortDesc: false,
      filter: null,
      loaded: false,
      slug: racaz.slug,
      Slug: racaz.columnName(racaz.slug),
      showAll: false,
      isBusy: true,
      items: [],
      octicons: octicons,
    }
  },
  created: function() {
    this.provideData();
    this.loaded = true;
  },
     mounted: function() {
  },
  methods: {
    provideData() {
      this.isBusy = true;
      // Here we don't set isBusy prop, so busy state will be handled by table itself
      let promise = axios.get(`/api/operations/${this.inv_id}`)
      return promise.then((response) => {
        if (!response.data[0]) {
          return []
        } else {
          this.fields = racaz.fieldsFillerOperations(Object.keys(response.data[0]));
          this.fields.push({
            key: 'actions',
            label: 'Opções',
            formatter: (value, key, item) => {
              //return `/${racaz.slug}/${response.data.id}`;
            }
          });
          this.items = response.data;
          this.totalRows = this.items.length;
          // Here we could override the busy state, setting isBusy to false
          this.isBusy = false;
          return (this.items);
        }
        })
        .catch(error => {
          // Here we could override the busy state, setting isBusy to false
          // this.isBusy = false
          // Returning an empty array, allows table to correctly handle busy state in case of error
          console.log(error)
          flash("Erro ao buscar operações.|danger")
          return []
        })
    },
    toggleInfo(item, index, button) {
      if (!item._showDetails) {
        Vue.set(item, '_showDetails', true)
        this.showAll = true
      } else if (item._showDetails == false) {
        Vue.set(item, '_showDetails', true)
        this.showAll = true
      } else {
        Vue.set(item, '_showDetails', false)
        this.showAll = false
      }
      this.$forceUpdate()
    },
    onFiltered(filteredItems) {
      // Trigger pagination to update the number of buttons/pages due to filtering
      this.totalRows = filteredItems.length
      this.currentPage = 1
    },
    allDetails() {
      //funcao para mostrar ou fechar todos os detalhes
      if (this.showAll == false) {
        for (const value of this.items) {
          value._showDetails = true
        }
        this.showAll = true
      } else {
        for (const value of this.items) {
          value._showDetails = false
        }
        this.showAll = false
      }
      this.$forceUpdate();
    },
  }
 }
</script>
