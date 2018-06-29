<template>
<div class="container-fluid justify-content-center" v-if="loaded === true">
  <!--   <div class="card"> -->
  <b-card :header="this.Slug" header-tag="header" title="">
    <!--     <div class="card-header"></div> -->
    <!--     <div class="card-body"> -->
    <p class="card-text">
      <b-row>
        <b-col md="6" class="my-1">
          <b-button size="sm" @click.stop="allDetails()">
            {{ this.showAll ? 'Fechar' : 'Abrir' }} todos detalhes
          </b-button>
          <b-button v-if="slug != 'invests'" size="sm" @click.stop="this.enlarge('create')">
            Criar {{Slug}}
          </b-button>
          <!--           <b-button v-if="slug == 'invests'" size="sm" @click.stop="this.enlarge('createTypeStocks')">
            Criar {{Slug}}
          </b-button> -->
          <b-dropdown size="sm" v-if="slug == 'invests'" right text="Criar investimento:">
            <b-dropdown-item @click.stop="this.enlarge('createTypeStocks')">Ações</b-dropdown-item>
            <b-dropdown-item @click.stop="this.enlarge('createTypeTreasuries')">Titulos do tesouro</b-dropdown-item>
            <b-dropdown-item @click.stop="this.enlarge('createTypeSecurities')">Renda fixa</b-dropdown-item>
            <b-dropdown-divider></b-dropdown-divider>
            <b-dropdown-item disabled>CDB</b-dropdown-item>
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
        <template slot="actions" slot-scope="row">
        <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
        <b-button size="sm" @click.stop="toggleInfo(row.item, row.index)">
          {{ row.detailsShowing ? 'Fechar' : 'Abrir' }} detalhes
        </b-button>
        <b-button v-if="slug != 'invests'" size="sm" @click.stop="this.enlarge('create',row.item)">
          Editar
        </b-button>
         <div v-if="slug == 'invests'">
        <b-button v-if="row.item.type === 'stock'" size="sm" @click.stop="this.enlarge('createTypeStocks',row.item)">
          Editar ação
        </b-button>        
        <b-button v-if="row.item.type === 'treasury'" size="sm" @click.stop="this.enlarge('createTypeTreasuries',row.item)">
          Editar título
        </b-button>        
        <b-button v-if="row.item.type === 'security'" size="sm" @click.stop="this.enlarge('createTypeSecurities',row.item)">
          Editar renda fixa
        </b-button>
        </div>
        <b-button v-if="slug != 'users'" size="sm" @click.stop="this.deleteconfirmation(row.item)">
          Excluir
        </b-button>
        <b-button v-if="slug == 'users'" size="sm" @click.stop="this.flash('Usuários não podem ser excluídos, somente desativados.|warning')">
          Excluir
        </b-button>
      </template>
        <template slot="row-details" slot-scope="row">
        <b-card>
<!--  funcao para nao mostrar o que sao variaveis          -->
          <ul v-for="(value, key) in row.item">
            <li v-if="key !== '_cellVariants' && key !== '_showDetails' && key !== 'index'" :key="key">{{ this.racaz.columnName(key) }}: {{ this.racaz.formtt([key,value]) }}</li>
          </ul>
        </b-card>
      </template>
      </b-table>
      <b-row>
        <b-col md="12" class="my-1 mx-auto">
          <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
        </b-col>
      </b-row>
      <enlarge>
        <create></create>
        <createinveststypestocks></createinveststypestocks>
        <createinveststypetreasuries></createinveststypetreasuries>
        <createinveststypesecurities></createinveststypesecurities>
      </enlarge>
      <!-- <enlarge> -->
      <!-- <createinvests></createinvests> -->
      <!-- </enlarge> -->
      <!--     </div> -->
    </p>
  </b-card>
  <!--   </div> -->
</div>
</template>

<script>
let items = {};
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
      modalInfo: {
        title: '',
        content: ''
      },
      loaded: false,
      slug: racaz.slug,
      Slug: racaz.columnName(racaz.slug),
      showAll: false,
      editRow: '',
      delRow: '',
      delRowMessage: '',
      delUrl: '',
      isBusy: false,
      items: [],
    }
  },
  created: function() {
    this.provideData();
    this.loaded = true;
  },
  mounted: function() {
    this.$bus.$on('updateindexedit', () => this.provideData());
  },
  methods: {
    provideData() {
      this.isBusy = true;
      // Here we don't set isBusy prop, so busy state will be handled by table itself
      let promise = axios.get('/api/index/' + racaz.slug)

      return promise.then((response) => {
          this.fields = racaz.fieldsFiller(Object.keys(response.data[0]));
          this.fields.push({
            key: 'actions',
            label: 'Opções',
            formatter: (value, key, item) => {
              return `/${racaz.slug}/${response.data.id}`;
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
    // updateDeleteModal(value, index) {
    //   this.delRow = Object.assign({}, this.delRow, value);
    //   console.log('index: ' + index);
    //   this.delRow.index = index + (this.perPage * (this.currentPage - 1));
    //   this.showModalDeleteRow();
    // },
    // updateEditModal(value, index) {
    //   this.editRow = Object.assign({}, this.editRow, value);
    //   value.index = index + (this.perPage * (this.currentPage - 1));
    //   console.log(this.editRow.index);
    //   console.log('ida');
    //   console.log(value);
    // },
    // updateData(value) {
    //   for (let k in this.items) {
    //     if (this.items[k].id == value.id) {
    //       Vue.set(this.items, [k], value);
    //     }
    //   }
    //   this.$root.$emit('bv::refresh::table', 'table');
    //   this.loaded = false;
    //   this.$nextTick(() => {
    //     this.loaded = true;
    //   });
    //   this.$forceUpdate();
    // },
    // deleteRow() {
    //   this.hideModalDeleteRow();
    //   document.getElementById("blur").classList.add("blur");
    //   $(".sk-cube-grid").fadeIn("slow");
    //   if (this.slug == 'invests') {
    //     this.delUrl = '/' + this.delRow.type + 's/' + this.slug + '/' + this.delRow.id + '/destroy'
    //     console.log(this.delUrl);
    //   } else {
    //     this.delUrl = '/' + this.slug + '/' + this.delRow.id + '/destroy'
    //     console.log(this.delUrl);
    //   }
    //   axios.delete(this.delUrl)
    //     .then(response => {
    //       console.log(response);
    //       flash(response.data.message + '|success');
    //       document.getElementById("blur").classList.remove("blur");
    //       $(".sk-cube-grid").fadeOut("slow");
    //       Vue.delete(this.items, this.delRow.index);
    //       this.loaded = false;
    //       this.$nextTick(() => {
    //         this.loaded = true;
    //       });
    //     })
    //     .catch(error => {
    //       console.log(error);
    //       document.getElementById("blur").classList.remove("blur");
    //       $(".sk-cube-grid").fadeOut("slow");
    //       if (error.response) {
    //         // The request was made and the server responded with a status code
    //         // that falls out of the range of 2xx
    //         console.log('Error 1 ');
    //         console.log(error.response.data);
    //         console.log(error.response.status);
    //         console.log(error.response.headers);
    //         flash('Erro ' + error.response.status + error.response.data.message + '|warning');
    //         //sessionStorage.setItem('errors2', error.response.status);
    //       } else if (error.request) {
    //         // The request was made but no response was received
    //         // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
    //         // http.ClientRequest in node.js
    //         console.log('Error 2 ' + error.request);
    //         flash(error.response.request + '|warning');
    //         //sessionStorage.setItem('errors2', error.request);
    //       } else {
    //         // Something happened in setting up the request that triggered an Error
    //         console.log('Error 3 ', error.message);
    //         flash(error.response.message + '|warning');
    //         //sessionStorage.setItem('errors2', error.request);
    //       }
    //       console.log(error.config);
    //       //this.delRowMessage = error.message;
    //       //this.showModalDeleteResult();
    //     });
    //   console.log('Deletado: ' + this.delRow.id);
    //   //this.showModalDeleteResult();
    //   //this.hideModal();
    // },
    //     showModalEditRow() {
    //       this.$refs.modalEditRow.show()
    //     },
    //     showModalDeleteRow() {
    //       this.$refs.modalDeleteRow.show()
    //     },
    //     hideModalDeleteRow() {
    //       this.$refs.modalDeleteRow.hide()
    //     },
    //     showModalDeleteResult() {
    //       this.$refs.modalDeleteResult.show()
    //     },
    //     hideModalDeleteResult() {
    //       this.$refs.modalDeleteResult.hide();
    //       this.loaded = false;
    //       this.$nextTick(() => {
    //         this.loaded = true;
    //       });
    //     }
  }
}
</script>
