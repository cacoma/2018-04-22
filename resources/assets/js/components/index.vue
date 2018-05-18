<template>
<div class="container-fluid justify-content-center" v-if="loaded === true">
  <!--   <div class="row justify-content-center"> -->
  <!--     <div class="col-md-12"> -->
  <div class="card">
    <div class="card-header">{{Slug}}</div>
    <div class="card-body">
      <!--  inserir padrao novo para mensagens de erro, de quando volta do servidor      -->
      <!--           <b-container fluid> -->

      <!-- User Interface controls -->
      <b-row>
        <b-col md="6" class="my-1">
          <b-button size="sm" @click.stop="allDetails()">
            {{ this.showAll ? 'Fechar' : 'Abrir' }} todos detalhes
          </b-button>
          <b-button size="sm" variant="success" :href="slug + '/create'">
            Criar {{ Slug }}
          </b-button>
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
      <!-- <b-table show-empty stacked="md" :items="items" :current-page="currentPage" :per-page="perPage" :filter="filter" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" @filtered="onFiltered"> -->
      <b-table ref="table" id="table" v-if="items.length > 0" show-empty responsive stacked="lg" :items="items" :fields="fields" :current-page="currentPage" :per-page="perPage" :filter="filter" :sort-by.sync="sortBy" :sort-desc.sync="sortDesc" @filtered="onFiltered">
        <template slot="actions" slot-scope="row">
        <!-- We use @click.stop here to prevent a 'row-clicked' event from also happening -->
<!--         <b-button size="sm" @click.stop="info(row.item, row.index, $event.target)" class="mr-1">
          Informações
        </b-button> -->
        <b-button size="sm" @click.stop="toggleInfo(row.item, row.index, $event.target)">
          {{ row.detailsShowing ? 'Fechar' : 'Abrir' }} detalhes
        </b-button>
        <b-button v-if="slug != 'invests'" size="sm" :href="`${row.value}/edit`">
          Editar
        </b-button>
        <b-button v-if="slug == 'invests'" size="sm" :href="`/${row.item.type}s${row.value}/edit`">
          Editar
        </b-button>
        <b-button v-if="slug != 'users'" size="sm" @click.stop="updateDeleteModal(row.item, row.index)">
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
<!--             <li v-if="key !== '_cellVariants' && key !== '_showDetails'" :key="key">{{ row.item }}</li> -->
          </ul>
        </b-card>
      </template>
      </b-table>
      <p v-else>Nenhum investimento foi encontrado.</p>

      <!-- Info modal -->
      <!--             <b-modal id="modalInfo" @hide="resetModal" :title="modalInfo.title" ok-only>
              <pre>{{ modalInfo.content }}</pre>
            </b-modal> -->

      <b-row>
        <b-col md="12" class="my-1 mx-auto">
          <b-pagination :total-rows="totalRows" :per-page="perPage" v-model="currentPage" class="my-0" />
        </b-col>
      </b-row>
      <!--           </b-container> -->

      <b-modal ref="modalDeleteRow" hide-footer title="Deletar">
        <div class="d-block text-center">
          <h3>Deletar este registro?</h3>
          <div v-for="(value, key) in this.delRow">
            <div v-if="key !== '_cellVariants' && key !== '_showDetails' && key !== 'index'" :key="key">{{ this.racaz.columnName(key) }}: {{ this.racaz.formtt([key,value]) }}</div>
            <!--             <li v-if="key !== '_cellVariants' && key !== '_showDetails'" :key="key">{{ row.item }}</li> -->
          </div>
        </div>
        <b-btn class="mt-3" variant="outline-danger" block @click="deleteRow()">Excluir registro</b-btn>
      </b-modal>

      <b-modal ref="modalDeleteResult" id="modalDeleteResult" hide-footer size="sm" centered title="Deletar">
        <div class="d-block text-center">
          <h3 v-text="this.delRowMessage"></h3>
          <b-btn class="mt-3" variant="outline-info" block @click="hideModalDeleteResult()">Fechar</b-btn>
        </div>
      </b-modal>

    </div>
  </div>
  <!--     </div> -->
  <!--   </div> -->
</div>
</template>

<script>
const items = {};
export default {
  props: ['items'],
  data() {
    return {
      keys: [],
      fields: [],
      currentPage: 1,
      perPage: 5,
      totalRows: items.length,
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
      delRow: '',
      delRowMessage: '',
      delUrl: ''
    }
  },
  created: function() {
    //verifica se o usuario tem algum investimento, caso nao tiver existe acima uma regra que mostra mensagem de erro padrão
    if (this.items[0]) {
      // cria os fields, pelas keys dos objetos e os deixa ordenaveis (sortable)
      //primeiro pega o nome das colunas
      //this.keys = Object.keys(this.items[0]);
      //funcao que trata o campo fields para o formato desejado (fields eh a variavel responsavel pela ordenacao e formato das colunas)
      //this.fields = racaz.fieldsFiller(this.keys);
      this.fields = racaz.fieldsFiller(Object.keys(this.items[0]));
      this.fields.push({
        key: 'actions',
        label: 'Opções',
        formatter: (value, key, item) => {
          return `/${racaz.slug}/${item.id}`;
        }
      });
      this.loaded = true;
    } else {
      this.loaded = true;
    }
  },
  computed: {
    //     sortOptions() {
    //       // Create an options list from our fields
    //       return this.fields
    //         .filter(f => f.sortable)
    //         .map(f => {
    //           return {
    //             text: f.label,
    //             value: f.key
    //           }
    //         })
    //     }
  },
  methods: {
    //     info(item, index, button) {
    //       this.modalInfo.title = `Row index: ${index}`
    //       this.modalInfo.content = JSON.stringify(item, null, 2)
    //       this.$root.$emit('bv::show::modal', 'modalInfo', button)
    //     },
    // flash(message){
    //   flash(message);
    // },
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
    //     resetModal() {
    //       this.modalInfo.title = ''
    //       this.modalInfo.content = ''
    //     },
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
      this.$forceUpdate()
      // capitalizeFirstLetter(string) {
      //   return string.charAt(0).toUpperCase() + string.slice(1);
      // }
    },
    updateDeleteModal(value, index) {
      //alert(value);
      //console.log(value.id);
      //Vue.set(this.delRow, [key] ,this.$refs[key][0].localValue);
      this.delRow = Object.assign({}, this.delRow, value);
      console.log('index: ' + index);
      this.delRow.index = index + (this.perPage * (this.currentPage - 1));
      this.showModalDeleteRow();
    },
    deleteRow() {
      this.hideModalDeleteRow();
      document.getElementById("blur").classList.add("blur");
      $(".sk-cube-grid").fadeIn("slow");
      if (this.slug == 'invests') {
        this.delUrl = '/' + this.delRow.type + 's/' + this.slug + '/' + this.delRow.id + '/destroy'
        ///${row.item.type}s${row.value}/edit
        console.log(this.delUrl);
      } else {
        this.delUrl = '/' + this.slug + '/' + this.delRow.id + '/destroy'
        console.log(this.delUrl);
      }
      axios.delete(this.delUrl)
        .then(response => {
          console.log(response);
          //this.delRowMessage = response.data.message;
          flash(response.data.message + '|success');
          document.getElementById("blur").classList.remove("blur");
          $(".sk-cube-grid").fadeOut("slow");
          //this.showModalDeleteResult();
          Vue.delete(this.items, this.delRow.index);

          //this.items.$remove(this.delRow.id)
          //              this.loaded = false;
          //              this.$nextTick(() => {
          //              this.loaded = true;
          //                });
        })
        .catch(error => {
          console.log(error);
          document.getElementById("blur").classList.remove("blur");
          $(".sk-cube-grid").fadeOut("slow");
          if (error.response) {
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx
            console.log('Error 1 ');
            console.log(error.response.data);
            console.log(error.response.status);
            console.log(error.response.headers);
            flash('Erro ' + error.response.status + error.response.data.message + '|warning');
            //sessionStorage.setItem('errors2', error.response.status);
          } else if (error.request) {
            // The request was made but no response was received
            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
            // http.ClientRequest in node.js
            console.log('Error 2 ' + error.request);
            flash(error.response.request + '|warning');
            //sessionStorage.setItem('errors2', error.request);
          } else {
            // Something happened in setting up the request that triggered an Error
            console.log('Error 3 ', error.message);
            flash(error.response.message + '|warning');
            //sessionStorage.setItem('errors2', error.request);
          }
          console.log(error.config);
          //this.delRowMessage = error.message;
          //this.showModalDeleteResult();
        });
      console.log('Deletado: ' + this.delRow.id);
      //this.showModalDeleteResult();
      //this.hideModal();
    },
    showModalDeleteRow() {
      this.$refs.modalDeleteRow.show()
    },
    hideModalDeleteRow() {
      this.$refs.modalDeleteRow.hide()
    },
    showModalDeleteResult() {
      this.$refs.modalDeleteResult.show()
    },
    hideModalDeleteResult() {
      this.$refs.modalDeleteResult.hide();
      this.loaded = false;
      this.$nextTick(() => {
        this.loaded = true;
      });
    }
  }
}
</script>
