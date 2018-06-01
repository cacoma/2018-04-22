<template>
<!-- <b-modal ref="modalEditRow" size="lg" hide-footer title="" v-model="this.show" no-close-on-backdrop no-close-on-esc hide-header-close> -->
  <div class="container-fluid justify-content-center" v-if="this.show">
    <b-alert variant="danger" dismissible :show="this.form.errors.any()" @dismissed="showDismissibleAlert=false">
      <div v-for="(value, key, index) in this.form.errors.errors">
        {{ index + 1 }}. {{ this.racaz.columnName(key) }}: {{ value[0] }}
      </div>
    </b-alert>
    <b-form @submit="onSubmit" @reset="onReset" @input="form.errors.clear($event.target.name)">
      <b-col>
        <b-form-group horizontal breakpoint="sm" :label-cols="2" :label="Slug" label-size="lg" label-class="font-weight-bold pt-0" class="mb-0">
          <div v-for="(value, key, index) in form">
            <b-form-group v-bind:id="key + 'label'" v-bind:label="this.racaz.columnName(key)" v-bind:label-for="key" v-if="key != 'errors' && key != 'modal' && key != 'created_at' && key != 'updated_at' && key != 'timestamp' && key != 'password' && key != 'remember_token'"
              :dusk="key">

              <!--              symbol -->
              <b-form-input v-if="key == 'symbol'" v-mask="['AAAA#.AA','AAAA##.AA']" :key="key" :value="value" :ref="key" v-on:input="updateData(key)" v-bind:id="value" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" oninput="setCustomValidity('')"
                oninvalid="this.setCustomValidity('Insira esta informação.')" required :dusk="key">
              </b-form-input>
              <!--           type -->
              <b-form-input v-if="key == 'type'" :key="key" :value="value" :ref="key" v-on:input="updateData(key)" v-bind:id="key" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira esta informação.')"
                required :dusk="key">
              </b-form-input>
              <!--           id -->
              <b-form-input v-if="key == 'id'" :key="key" :value="value" :ref="key" v-bind:id="key" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" disabled :dusk="key">
              </b-form-input>
              <!--           name -->
              <b-form-input v-if="key == 'name'" :key="key" :value="value" :ref="key" v-on:input="updateData(key)" v-bind:id="key" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira esta informação.')"
                required :dusk="key">
              </b-form-input>
              <!--           cnpj -->
              <b-form-input v-if="key == 'cnpj'" v-mask="['###.###.###-##', '##.###.###/####-##']" :key="key" :value="value" :ref="key" v-on:input="updateData(key)" v-bind:id="key" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" oninput="setCustomValidity('')"
                oninvalid="this.setCustomValidity('Insira esta informação.')" required :dusk="key">
              </b-form-input>
              <!-- email -->
              <b-form-input v-if="key == 'email'" type="email" :key="key" :value="value" :ref="key" v-on:input="updateData(key)" v-bind:id="key" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira esta informação.')"
                required :dusk="key">
              </b-form-input>
              <!-- permissao -->
              <b-form-select v-if="key == 'role_id'" :key="key" :value="value" :ref="key" v-on:input="updateData(key)" v-bind:id="key" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" class="mb-3" :dusk="key">
                <option value="1">Admin</option>
                <option value="2">Usuário</option>
              </b-form-select>
              <p class="text-danger" v-if="form.errors.has(key)" v-text="form.errors.get(key)" dusk="createerrorsdisplay">
              </p>
            </b-form-group>
          </div>
        </b-form-group>
      </b-col>
      <b-row align-h="end">
        <b-col md="4" offset-md="1">
          <b-button type="submit" variant="success" :disabled="this.form.errors.any()" dusk="createsubmit">{{ this.editMode ? 'Atualizar' : 'Inserir' }}</b-button>
          <b-button type="reset" variant="danger" dusk="createreset">{{ 'Fechar' }}</b-button>
        </b-col>
      </b-row>
    </b-form>
  </div>
<!-- </b-modal> -->
</template>
<script>
import {
  Errors,
  Form,
  Modal
} from './../javascript/classes.js';

export default {
  props: ['data'],
  data() {
    return {
      form: new Form({}),
      show: false,
      slug: racaz.slug,
      Slug: racaz.columnName(racaz.pathArray[1]),
      pathArray: racaz.pathArray,
      editMode: false
    }
  },
  created: function() {
    window.events.$on('create', (item) => this.populateData(item));
    if (this.data){
      this.populateData(this.data);
    }
  },
  computed: {},
  methods: {
    updateData(key) {
      Vue.set(this.form, [key], this.$refs[key][0].localValue);
    },
    onSubmit(evt) {
      evt.preventDefault();
      if (!this.editMode) {
        this.form.post('/' + this.pathArray[1] + '/store')
          .then(data => {
            console.log('promise Success store ');
            console.log(data);
            // this.show = false;
            this.$bus.$emit('updateindexedit', this.form);
            this.$bus.$emit('enlargeclose');
          })
          .catch(errors => {
            console.log('promise Fail store ');
            console.log(errors);
          });
      } else {
        this.form.patch('/' + this.pathArray[1] + '/' + this.form.id)
          .then(data => {
            console.log('promise update success' + data);
            // this.show = false;
            this.$bus.$emit('updateindexedit', this.form);
            this.$bus.$emit('enlargeclose');
          })
          .catch(errors => {
            console.log('promise update fail ' + errors);
          });
      }
    },
    onReset(evt) {
      evt.preventDefault();
      if (!this.editMode) {
        this.form.reset();
        /* Reset our form values */
        /* Trick to reset/clear native browser form validation state */
        // this.show = false;
        this.$bus.$emit('enlargeclose');
      } else {
        // this.show = false;
        this.$bus.$emit('enlargeclose');
      }
    },
    populateData(value) {
      if (value) {
        this.editMode = true;
        console.log('populate chamado');
        for (let k in value) {
          if (typeof value[k] !== 'function') {
            Vue.set(this.form, [k], value[k]);
          }
        }
        this.show = true;
      } else {
        this.editMode = false;
        this.form.reset;
        axios.get('/api/columns/' + this.pathArray[1])
          .then(response => {
            response.data.forEach(value => {
              Vue.set(this.form, [value], '');
            });
          })
          .catch(error => {
            console.log(error);
          });
        this.show = true;
      }
    }
  },
}
</script>
