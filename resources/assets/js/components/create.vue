<template>
<div class="container-fluid justify-content-center">
  <div class="card">
    <div class="card-header">Cadastro de {{Slug}}</div>
    <div class="card-body">
      <div class="container-fluid">
        <b-button size="sm" variant="outline-success" :href="'/' + slug">
          Ir para indexador de {{Slug}}
        </b-button>
        <b-card bg-variant="light">
            <b-alert variant="danger" dismissible :show="this.form.errors.any()" @dismissed="showDismissibleAlert=false">
                <div v-for="(value, key, index) in this.form.errors.errors">
                  {{ index + 1 }}. {{ this.racaz.columnName(key) }}: {{ value[0] }}
                </div>
            </b-alert>
          <b-form @submit="onSubmit" @reset="onReset" v-if="this.show" @input="form.errors.clear($event.target.name)">
            <b-col>
              <b-form-group horizontal breakpoint="sm" :label-cols="2" :label="Slug" label-size="lg" label-class="font-weight-bold pt-0" class="mb-0">
                <div v-for="(value, key, index) in form">
                  <b-form-group v-bind:id="key + 'label'" v-bind:label="this.racaz.columnName(key)" v-bind:label-for="key" v-if="key != 'errors' && key != 'modal' && key != 'created_at' && key != 'updated_at' && key != 'timestamp'">

                    <!--              symbol -->
                    <b-form-input v-if="key == 'symbol'" v-mask="['AAAA#.AA','AAAA##.AA']" :key="key" :value="value" :ref="key" v-on:input="updateData(key)" v-bind:id="value" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira esta informação.')"
                      required>
                    </b-form-input>
                    <!--           type -->
                    <b-form-input v-if="key == 'type'" :key="key" :value="value" :ref="key" v-on:input="updateData(key)" v-bind:id="key" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira esta informação.')"
                      required>
                    </b-form-input>
                    <!--           id -->
                    <b-form-input v-if="key == 'id'" :key="key" :value="value" :ref="key" v-bind:id="key" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" disabled>
                    </b-form-input>
                    <!--           name -->
                    <b-form-input v-if="key == 'name'" :key="key" :value="value" :ref="key" v-on:input="updateData(key)" v-bind:id="key" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira esta informação.')"
                      required>
                    </b-form-input>
                    <!--           cnpj -->
                    <b-form-input v-if="key == 'cnpj'" v-mask="['###.###.###-##', '##.###.###/####-##']" :key="key" :value="value" :ref="key" v-on:input="updateData(key)" v-bind:id="key" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" oninput="setCustomValidity('')"
                      oninvalid="this.setCustomValidity('Insira esta informação.')" required>
                    </b-form-input>
                    <!-- email -->
                    <b-form-input v-if="key == 'email'" type="email" :key="key" :value="value" :ref="key" v-on:input="updateData(key)" v-bind:id="key" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira esta informação.')"
                      required>
                    </b-form-input>
                    <!-- permissao -->
                    <b-form-select v-if="key == 'role_id'" :key="key" :value="value" :ref="key" v-on:input="updateData(key)" v-bind:id="key" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" class="mb-3">
                      <option value="1">Admin</option>
                      <option value="2">Usuário</option>
                    </b-form-select>
                    <!--           created updated timestamp-->
                    <!--             <b-form-input v-if="key == 'created_at' || key == 'updated_at' || key == 'timestamp'" type="date" :key="key" :value="value" :ref="key" v-on:input="updateData(key)" v-bind:id="key" v-bind:name="key" v-bind:class="{ 'is-invalid': form.errors.has(key) }" required>
            </b-form-input> -->
                    <p class="text-danger" v-if="form.errors.has(key)" v-text="form.errors.get(key)">
                    </p>
                  </b-form-group>
                </div>
              </b-form-group>
            </b-col>
            <b-row align-h="end">
              <b-col md="3" offset-md="1">
                <b-button type="submit" variant="success" :disabled="this.form.errors.any()">{{ this.data ? 'Atualizar' : 'Inserir' }}</b-button>
                <b-button type="reset" variant="danger">{{ this.data ? 'Restaurar' : 'Limpar' }}</b-button>
              </b-col>
            </b-row>
          </b-form>
        </b-card>

        <!-- modal de sucesso -->
        <b-modal ref="myModalRef" id="myModalRef" hide-footer size="sm" centered v-model="form.modal.any()">
          <div class="d-block text-center">
            <h3 v-text="form.modal.get('message')"></h3>
            <b-button size="sm" variant="outline-success" :href="'/' + slug">
              Ir para indexador de {{Slug}}
            </b-button>
          </div>
        </b-modal>
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
} from './../javascript/classes.js';

export default {
  props: ['data'],
  data() {
    return {
      form: new Form({
        //           symbol: 'teste',
        //           type: ''
      }),
      show: true,
      slug: racaz.slug,
      Slug: racaz.columnName(racaz.pathArray[1]),
      pathArray: racaz.pathArray,
    }
  },
  created: function() {
    if (!this.data) {
      axios.get('/api/columns/' + this.pathArray[1])
        .then(response => {
          //console.log(response);
          response.data.forEach(value => {
            Vue.set(this.form, [value], '');
            //this.form[value] = ''
          });
          this.show = false;
          this.$nextTick(() => {
            this.show = true
          });
        })
        .catch(error => {
          console.log(error);
        });
    } else {
      for (var k in this.data) {
        if (typeof this.data[k] !== 'function') {
          //console.log("Key is " + k + ", value is" + this.data[k]);
          Vue.set(this.form, [k], this.data[k]);
          //Vue.set(this.$refs[k][0].localValue, [k], this.data[k]);
        }
      }
      //             for (var k in this.data){
      //              if (typeof this.data[k] !== 'function') {
      //                     //console.log("Key is " + k + ", value is" + this.data[k]);
      //                //Vue.set(this.form, [k], this.data[k]);
      //                Vue.set(this.$refs, [k][0].localValue, this.data[k]);
      //        }
      //     }
      //         this.form = Object.assign({}, this.form, this.data);
      //       this.show = false;
      //         this.$nextTick(() => {
      //           this.show = true
      //         });
    }
  },
  computed: {},
  methods: {
    updateData(key) {
      //this.$emit('b-form-input', this.form.symbol)
      //console.log(key)
      // this.$nextTick(() => {
      Vue.set(this.form, [key], this.$refs[key][0].localValue);
      //this.form[key] = this.$refs[key][0].localValue
      // });
    },
    onSubmit(evt) {
      evt.preventDefault();
      if (!this.data) {
        this.form.post('/' + this.pathArray[1] + '/store')
          .then(data => {
            console.log('promise Success ');
            console.log(data);
            //flash(data.message+'|success');
            //           this.show = false;
            //           this.$nextTick(() => {
            //             this.show = true
            //           });
          })
          .catch(errors => {
            console.log('promise Fail ');
            console.log(errors);
          });
      } else {
        this.form.patch('/' + this.pathArray[1] + '/' + this.data.id)
          .then(data => {
            console.log('promise update success' + data);
            //flash(data.message+'|success');
            //           this.show = false;
            //           this.$nextTick(() => {
            //             this.show = true
            //           });
          })
          .catch(errors => {
            console.log('promise update fail ' + errors);
          });
      }
    },
    onReset(evt) {
      evt.preventDefault();
      if (!this.data) {
        this.form.reset();
        /* Reset our form values */
        /* Trick to reset/clear native browser form validation state */
        this.show = false;
        this.$nextTick(() => {
          this.show = true
        });
      } else {
        for (var k in this.data) {
          if (typeof this.data[k] !== 'function') {
            //console.log("Key is " + k + ", value is" + this.data[k]);
            Vue.set(this.form, [k], this.data[k]);
            //Vue.set(this.$refs[k][0].localValue, [k], this.data[k]);
          }
        }
      }
    },
  }
}
</script>
