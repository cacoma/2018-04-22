<template>
<div class="container-fluid justify-content-center">
  <div class="card">
    <div class="card-header">Cadastro de {{Slug}}</div>
    <div class="card-body">
      <div class="container-fluid">

        <b-card bg-variant="light">
          <b-alert variant="danger" dismissible :show="this.form.errors.any()" @dismissed="showDismissibleAlert=false">
            <div v-for="(value, key, index) in this.form.errors.errors">
              {{ index + 1 }}. {{ this.racaz.columnName(key) }}: {{ value[0] }}
            </div>
          </b-alert>
          <b-form @submit="onSubmit" @reset="onReset" v-if="this.show" @input="form.errors.clear($event.target.name)">
            <b-col>
              <b-form-group horizontal breakpoint="sm" :label-cols="2" :label="Slug" label-size="lg" label-class="font-weight-bold pt-0" class="mb-0">
<!--               <b-form-group horizontal breakpoint="sm" class="mb-0">       -->
                  
                <div v-for="(value, key, index) in form">
                  <b-form-group v-bind:id="key + 'label'" v-bind:label="this.racaz.columnName(key)" v-bind:label-for="key" v-if="key != 'errors' && key != 'modal' && key != 'created_at' && key != 'updated_at' && key != 'timestamp'">
                    <b-form-input v-if="key == 'symbol'" v-mask="'AAAA#.AA'" :key="key" :value="value" :ref="key" v-on:input="updateData(key)" v-bind:id="value" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira esta informação.')"
                      required>
                    </b-form-input>
                    <p class="text-danger" v-if="form.errors.has(key)" v-text="form.errors.get(key)">
                    </p>
                  </b-form-group>
                </div>
              </b-form-group>
            </b-col>
            <b-row align-h="end">
              <b-col cols="3" offset-md="1">
                <b-button type="submit" variant="success" :disabled="this.form.errors.any()">Inserir</b-button>
                <b-button type="reset" variant="danger">Limpar</b-button>
              </b-col>
            </b-row>
          </b-form>
          <br>
          <b-row align-h="end">
            <b-col cols="3">
          <b-button type="submit" variant="success" :disabled="this.form.errors.any()" @click="massInsert()">Inserir em massa</b-button>
              </b-col>
           </b-row>
                  </b-card>
              </div>
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

export default {
  data() {
    return {
      form: new Form({
        symbol: '',
        //           symbol: 'teste',
        //           type: ''
      }),
      show: true,
      slug: racaz.slug,
      Slug: racaz.columnName(racaz.pathArray[1]),
      pathArray: racaz.pathArray,
    }
  },
  methods: {
    updateData(key) {
      Vue.set(this.form, [key], this.$refs[key][0].localValue);
    },
    onSubmit(evt) {
      evt.preventDefault();
        this.form.post('/' + this.pathArray[1] + '/store')
          .then(data => {
            console.log('promise Success ');
            console.log(data);
          })
          .catch(errors => {
            console.log('promise Fail ');
            console.log(errors);
          });
       },
      onReset(evt) {
      evt.preventDefault();
        this.form.reset();
        /* Reset our form values */
        /* Trick to reset/clear native browser form validation state */
        this.show = false;
        this.$nextTick(() => {
          this.show = true
        });
      },
     massInsert() {
      document.getElementById("blur").classList.add("blur");
      $(".sk-cube-grid").fadeIn("slow");
        axios.get('/' + this.pathArray[1] + '/massinsert')
          .then(data => {
            console.log('promise Success ');
            console.log(data);
          document.getElementById("blur").classList.remove("blur");
          $(".sk-cube-grid").fadeOut("slow");
            flash(data.data.message);
          })
          .catch(errors => {
            console.log('promise Fail ');
            console.log(errors);
          document.getElementById("blur").classList.remove("blur");
          $(".sk-cube-grid").fadeOut("slow");
          });
       },
  }
}
</script>