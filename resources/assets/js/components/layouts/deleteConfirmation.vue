<template>
<b-modal dusk="deleteModal" ref="deleteModal" id="deleteModal" hide-footer size="md" centered v-model="this.show" header-bg-variant="danger" no-close-on-backdrop no-close-on-esc hide-header-close>
  <div class="d-block text-center">
    <h5 v-text="this.title"></h5>
    <div v-for="(value, key) in this.body">
      <div v-if="key !== '_cellVariants' && key !== '_showDetails' && key !== 'index'" :key="key">{{ this.racaz.columnName(key) }}: {{ this.racaz.formtt([key,value]) }}</div>
    </div>

    <b-btn class="mt-3" variant="danger" @click="deleteRow()">Excluir registro</b-btn>
    <b-btn class="mt-3" variant="primary" @click="hide()">Cancelar</b-btn>
  </div>
</b-modal>
</template>
<script>
export default {
  props: ['message'],
  data() {
    return {
      show: false,
      body: {},
      title: '',
      dusk: 'ok',
    }
  },
  created() {
    if (this.item) {
      this.flash(this.item)
    }
    window.events.$on('deleteconfirmation', (item) => this.deleteconfirmation(item))
  },
  methods: {
    deleteconfirmation(item) {
      this.body = {};
      this.title = 'Voce tem certeza que deseja deletar este registro?';
      if (this.isJson(item)) {
        this.body = item;

        console.log("isJson");
        this.show = true;
      } else {
        console.log(item);
        this.body = item;
        this.show = true;
        console.log('notJson');
      }
    },
    hide() {
      this.show = false;
    },
    isJson(item) {
      item = typeof item !== "string" ?
        JSON.stringify(item) :
        item;

      try {
        item = JSON.parse(item);
      } catch (e) {
        return false;
      }

      if (typeof item === "object" && item !== null) {
        return true;
      }

      return false;
    },
    deleteRow() {
      loadingon();
      this.hide();
      if (racaz.slug == 'invests') {
        this.delUrl = '/' + this.body.type + 's/' + racaz.slug + '/' + this.body.id + '/destroy'
        console.log(this.delUrl);
      } else {
        this.delUrl = '/' + racaz.slug + '/' + this.body.id + '/destroy'
        console.log(this.delUrl);
      }
      axios.delete(this.delUrl)
        .then(response => {
          console.log(response);
          this.$bus.$emit('updateindexedit');
          loadingoff();
          flash(response.data.message + '|success');
        })
        .catch(error => {
          console.log(error);
          loadingoff();
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
    },

  }
}
</script>
