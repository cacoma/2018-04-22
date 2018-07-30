<template>
<div class="container-fluid justify-content-center" v-if="loaded === true">
  <b-card :header="this.Slug" header-tag="header" title="">
    <p class="card-text">
      <b-row>
        <b-col md="6" class="my-1">
          <b-button size="sm" @click.stop="allDetails()">
            {{ this.showAll ? 'Fechar' : 'Abrir' }} todos detalhes
          </b-button>
          <b-dropdown size="sm" right text="Criar investimento:">
            <b-dropdown-item @click.stop="this.enlarge('createTypeStocks')">Ações</b-dropdown-item>
            <b-dropdown-item @click.stop="this.enlarge('createTypeTreasuries')">Titulos do tesouro</b-dropdown-item>
            <b-dropdown-item @click.stop="this.enlarge('createTypeSecurities')">Renda fixa</b-dropdown-item>
            <b-dropdown-item @click.stop="this.enlarge('createTypeFunds')">Fundos</b-dropdown-item>
          </b-dropdown>
        </b-col>
      </b-row>
      <b-row>
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
      </b-row>

      <!-- Main table element -->
      <b-table ref="table" id="table" show-empty responsive stacked="lg" :busy.sync="isBusy" :items="items" :fields="fields" :current-page="currentPage" :per-page="perPage" :filter="filter" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" @filtered="onFiltered">
        <template slot="empty">
          Nenhum investimento encontrado.
        </template>
        <template slot="emptyfiltered">
          Nenhum investimento encontrado com este filtro.
        </template>
        <template slot="actions" slot-scope="row" cols="1">
<b-row>
  <b-col>
        <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
<!--           {{ row.detailsShowing ? octicons.alert.toSVG() : octicons.alert.toSVG() }} detalhes -->
   <b-button size="sm" variant="secondary" @click.stop="toggleInfo(row.item, row.index)">
        <span v-html="octicons.search.toSVG()"></span>
        </b-button>        
<!--         <b-button size="sm" variant="secondary" v-bind:class="{ 'fas fa-search-plus': row.detailsShowing, 'fas fa-search-minus': !row.detailsShowing }" @click.stop="toggleInfo(row.item, row.index)">
        </b-button> -->
        <b-button v-if="row.item.type === 'stock'" size="sm" variant="primary" @click.stop="this.enlarge('createTypeStocks',row.item)">
          <span v-html="octicons.pencil.toSVG()"></span>
        </b-button>
        <b-button v-if="row.item.type === 'treasury'" size="sm" variant="primary" @click.stop="this.enlarge('createTypeTreasuries',row.item)">
          <span v-html="octicons.pencil.toSVG()"></span>
        </b-button>
        <b-button v-if="row.item.type === 'security'" size="sm" variant="primary" @click.stop="this.enlarge('createTypeSecurities',row.item)">
          <span v-html="octicons.pencil.toSVG()"></span>
        </b-button>
           <b-button v-if="row.item.type === 'fund'" size="sm" variant="primary" @click.stop="this.enlarge('createTypeFunds',row.item)">
          <span v-html="octicons.pencil.toSVG()"></span>
        </b-button>
<!--           </b-col>
    <b-col> -->
          <b-button v-if="row.item.type === 'stock'" size="sm" variant="success" @click.stop="this.enlarge('sellTypeStocks',row.item)">
          <span v-html="octicons.diff.toSVG()"></span>
        </b-button>
        <b-button v-if="row.item.type === 'treasury'" size="sm" variant="success" @click.stop="this.enlarge('sellTypeTreasuries',row.item)">
          <span v-html="octicons.diff.toSVG()"></span>
        </b-button>
        <b-button v-if="row.item.type === 'security'" size="sm" variant="success" @click.stop="this.enlarge('sellTypeSecurities',row.item)">
          <span v-html="octicons.diff.toSVG()"></span>
        </b-button>
           <b-button v-if="row.item.type === 'fund'" size="sm" variant="success" @click.stop="this.enlarge('sellTypeFunds',row.item)">
          <span v-html="octicons.diff.toSVG()"></span>
        </b-button>
        <b-button size="sm" variant="danger" @click.stop="this.deleteconfirmation(row.item)">
          <span v-html="octicons.trashcan.toSVG()"></span>
        </b-button>
          </b-col>
          </b-row>
      </template>
        <template slot="row-details" slot-scope="row">
        <b-card>
<!--  funcao para nao mostrar o que sao variaveis          -->
         <div v-if="row.item.operation">
         <indexoperations  :inv_id="row.item.id"></indexoperations>
          </div>
           <br>
            <b-card header="Detalhes do investimento" header-tag="header" title="">
          <ul v-for="(value, key) in row.item">
            <li v-if="key !== '_cellVariants' && key !== '_showDetails' && key !== 'index' && key !== 'operation'" :key="key">{{ this.racaz.columnNameInvest(key) }}: {{ this.racaz.formtt([key,value]) }}</li>
          </ul>
          </b-card>
        </b-card>
      </template>
      </b-table>
      <b-row>
        <b-col md="12" class="my-1 mx-auto">
          <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
        </b-col>
      </b-row>
      <enlarge>
        <createinveststypestocks></createinveststypestocks>
        <createinveststypetreasuries></createinveststypetreasuries>
        <createinveststypesecurities></createinveststypesecurities>
        <createinveststypefunds></createinveststypefunds>

        <sellinveststypestocks></sellinveststypestocks>
        <sellinveststypetreasuries></sellinveststypetreasuries>
        <sellinveststypesecurities></sellinveststypesecurities>
        <sellinveststypefunds></sellinveststypefunds>
      </enlarge>
    </p>
  </b-card>
</div>
</template>

<script>
let items = {};
var octicons = require("octicons");
export default {
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
    this.$bus.$on('updateindexinvestedit', () => this.provideData());
  },
  methods: {
    provideData() {
      this.isBusy = true;
      // Here we don't set isBusy prop, so busy state will be handled by table itself
      let promise = axios.get('/api/index/invests')
      return promise.then((response) => {
          this.fields = racaz.fieldsFillerInvests(Object.keys(response.data[0]));
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
        })
        .catch(error => {
          // Here we could override the busy state, setting isBusy to false
          // this.isBusy = false
          // Returning an empty array, allows table to correctly handle busy state in case of error
          console.log(error)
          flash("Erro ao buscar investimentos.|danger")
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
