<template>
<div class="container-fluid justify-content-center" v-if="this.show">
  <b-alert variant="danger" dismissible :show="this.form.errors.any()" @dismissed="showDismissibleAlert=false">
    <div v-for="(value, key, index) in this.form.errors.errors">
      {{ index + 1 }}. {{ this.racaz.columnName(key) }}: {{ value[0] }}
    </div>
  </b-alert>
  <b-form @submit="onSubmit" @reset="onReset" @input="form.errors.clear($event.target.name)" id="createtypetreasuryform" dusk="createtypetreasurieform" autocomplete="off">
    <input autocomplete="false" name="hidden" type="text" style="display:none;">
    <b-form-row v-if="!this.editMode">
      <!-- <b-col>
        <b-form-group label="Operação de: ">
          <b-form-radio-group id="signal" name="signal" buttons button-variant="outline-primary" size="md" v-model="form.signal" :options="optionsSignal" v-bind:class="{ 'is-invalid': form.errors.has('signal') }" required/>
        </b-form-group>
        <p class="text-danger" v-if="form.errors.has('signal')" v-text="form.errors.get('signal')">
        </p>
      </b-col> -->
    </b-form-row>
    <b-form-row>
      <b-col>
        <b-form-group id="namelabel" label="Código do titulo:" label-for="name">
          <b-tooltip ref="tooltipCode" v-show="tipCode" target="name" placement="topright">
            <strong v-text="tipCode"></strong>
          </b-tooltip>
          <input type="text" list="listtreasuries" placeholder="Código da título" v-model="form.name" autocomplete="off" class="form-control" v-bind:class="{ 'is-invalid': form.errors.has('name') }" name="name" id="name" ref="name" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o treasury')"
            required>
          <datalist id="listtreasuries">
                   <option v-for="resulttreasury in resulttreasuries" v-bind:value="resulttreasury">{{ resulttreasury }}</option>
                </datalist>
          <p class="text-danger" v-if="form.errors.has('name')" v-text="form.errors.get('name')">
          </p>

          </b-tooltip>
        </b-form-group>
      </b-col>
      <b-col>
        <b-form-group id="datelabel" label="Data do investimento" label-for="date_invest">
          <datepicker id="date_invest" :language="ptBR" name="date_invest" v-model="form.date_invest" autocomplete="nope" input-class="form-control" v-bind:class="{ 'is-invalid': form.errors.has('date_invest') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira a data do investimento.')"
            placeholder="Clique aqui para inserir a data." format="dd/MM/yyyy" :disabledDates="this.disabledDates" required dusk="datepicker">
          </datepicker>
<!--          <b-form-input type="date" id="date_invest" name="date_invest" v-model="form.date_invest" input-class="form-control" v-bind:class="{ 'is-invalid': form.errors.has('date_invest') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira a data do investimento.')"
            dusk="datepicker" required >
          </b-form-input> -->
          <p class="text-danger" v-if="form.errors.has('date_invest')" v-text="form.errors.get('date_invest')">
          </p>
        </b-form-group>
      </b-col>
      <b-col>
        <b-form-group id="brokerlabel" label="Corretora:" label-for="broker_name">
          <b-tooltip ref="tooltipBroker" target="broker_name" v-show="tipBroker" placement="topright">
            <strong v-text="tipBroker"></strong>
          </b-tooltip>
          <input type="text" list="listbrokers" placeholder="Corretora" v-model="form.broker_name" autocomplete="off" class="form-control" v-bind:class="{ 'is-invalid': form.errors.has('broker_name') }" name="broker_name" id="broker_name" ref="broker_name" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira a corretora')"
                 required>
          <datalist id="listbrokers">
                   <option v-for="resultbroker in resultbrokers" v-bind:value="resultbroker" class="text-light bg-dark">{{resultbroker}}</option>
            </datalist>
          <p class="text-danger" v-if="form.errors.has('broker_name')" v-text="form.errors.get('broker_name')">
          </p>
        </b-form-group>
      </b-col>
    </b-form-row>
    <!--   segunda linha -->
    <b-form-row>
      <b-col>
        <b-form-group id="pricelabel" label="Preço do titulo" label-for="price">
          <b-input-group prepend="R$">
<!--             <money id="price" name="price" v-model="form.price" v-bind="money" class="form-input input-lg form-control" v-bind:class="{ 'is-invalid': form.errors.has('price') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o preco')"
              required>
            </money>            -->
            <quantinput id="price" name="price" v-model="form.price" v-bind="quantInputReais" class="form-input input-lg form-control" v-bind:class="{ 'is-invalid': form.errors.has('price') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o preco')"
              required>
            </quantinput>
          </b-input-group>
          <p class="text-danger" v-if="form.errors.has('price')" v-text="form.errors.get('price')">
          </p>
        </b-form-group>
      </b-col>
      <b-col>
        <b-form-group id="quantlabel" label="Quantidade:" label-for="quant">
<!--           <money id="quant" name="quant" v-model="form.quant" v-bind="money" class="form-input input-lg form-control"  v-bind:class="{ 'is-invalid': form.errors.has('quant') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira a quantidade.')"
                 required>
          </money>           -->
          <quantinput id="quant" name="quant" v-model="form.quant" v-bind="quantInput" class="form-input input-lg form-control"  v-bind:class="{ 'is-invalid': form.errors.has('quant') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira a quantidade.')"
                 required>
          </quantinput>
          <p class="text-danger" v-if="form.errors.has('quant')" v-text="form.errors.get('quant')">
          </p>
        </b-form-group>
      </b-col>
      <b-col>
        <b-form-group id="broker_feelabel" label="Corretagem:" label-for="broker_fee">
          <b-input-group prepend="R$">
<!--             <money id="broker_fee" name="broker_fee" v-model="form.broker_fee" v-bind="money" class="form-input input-lg form-control" v-bind:class="{ 'is-invalid': form.errors.has('broker_fee') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o valor da corretagem.')"
              required>
            </money>             -->
            <quantinput id="broker_fee" name="broker_fee" v-model="form.broker_fee" v-bind="quantInputReais" class="form-input input-lg form-control" v-bind:class="{ 'is-invalid': form.errors.has('broker_fee') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o valor da corretagem.')"
              required>
            </quantinput>
            <p class="text-danger" v-if="form.errors.has('broker_fee')" v-text="form.errors.get('broker_fee')">
            </p>
          </b-input-group>
        </b-form-group>
      </b-col>
      <b-col>
        <b-form-group id="ratelabel" label="Taxa:" label-for="rate">
          <b-input-group append="%">
<!--             <money id="rate" name="rate" v-model="form.rate" v-bind="money" class="form-input input-lg form-control"  v-bind:class="{ 'is-invalid': form.errors.has('rate') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira a taxa de retorno.')"
                   required>
            </money>                 -->
            <quantinput id="rate" name="rate" v-model="form.rate" v-bind="quantInputPercent" class="form-input input-lg form-control"  v-bind:class="{ 'is-invalid': form.errors.has('rate') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira a taxa de retorno.')"
                   required>
            </quantinput>
<!--             <input v-twodecimals id="rate" name="rate" v-model="form.rate" class="form-input input-lg form-control"  v-bind:class="{ 'is-invalid': form.errors.has('rate') }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira a taxa de retorno.')" required>
            </input> -->
            <p class="text-danger" v-if="form.errors.has('rate')" v-text="form.errors.get('rate')">
            </p>
          </b-input-group>
        </b-form-group>
      </b-col>
      <b-col>
        <b-form-group id="totallabel" label="Total:" label-for="total">
          <b-input-group prepend="R$">
<!--             <money id="total" :value="total" v-bind="money" class="form-input input-lg form-control" disabled dusk="createtypetreasurytotal">
            </money>             -->
            <quantinput id="total" :value="total" v-bind="quantInputReais" class="form-input input-lg form-control" disabled dusk="createtypetreasurytotal">
            </quantinput>
          </b-input-group>
        </b-form-group>
      </b-col>
    </b-form-row>
    <b-row align-h="end">
      <b-col md="6" offset-md="1">
        <b-button type="submit" variant="success" :disabled="form.errors.any()" dusk="createtypetreasurysubmit"> {{ this.editMode ? 'Atualizar' : 'Inserir' }}</b-button>
        <b-button type="reset" variant="danger" v-if="this.Slug2 !== 'create'">{{ 'Fechar' }}</b-button>
        <b-button variant="primary" @click="formReset" dusk="createformreset">{{ 'Limpar' }}</b-button>
      </b-col>
    </b-row>
  </b-form>
</div>
<!-- </b-modal> -->
</template>
<script>
  /*jshint esversion: 6 */
  import {
    Errors,
    Form,
    Modal
  } from './../../javascript/classes.js';

  import Datepicker from 'vuejs-datepicker';
  import {
    ptBR
  } from 'vuejs-datepicker/dist/locale';

  export default {
    components: {
      Datepicker
    },

    data() {
      return {
        form: new Form({
          signal: 'buy',
          name: '',
          date_invest: '',
          broker_name: '',
          price: '',
          quant: '',
          rate: '',
          rateFloat: '',
          broker_fee: '',
        }),
        money: {
          decimal: ',',
          thousands: '.',
          precision: 2,
          masked: false,
        },
        quantInput: {
          precision: 4,
          masked: false,
        },
        quantInputReais: {
          precision: 2,
          masked: false,
        },
        quantInputPercent: {
          precision: 4,
          masked: false,
        },
        csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        results: [],
        resultbrokers: [],
        resulttreasuries: [],
        show: false,
        disabledDates: racaz.dateInvestLimit.disabledDates,
        ptBR: ptBR,
        tipBroker: 'Procurar corretora',
        tipCode: 'Procurar título',
        optionsSignal: [{
            text: 'Compra',
            value: 'buy'
          },
          {
            text: 'Venda',
            value: 'sell'
          },
        ],
        editMode: false,
        Slug2: racaz.columnName(racaz.pathArray[2]),
      }
    },
    watch: {
      // whenever code changes, this function will run
      //     'form.code': function(newCode, oldCode) {
      //       this.debouncedFormCode();
      //     },
    },
    created: function() {
      window.events.$on('createTypeTreasuries', (item) => this.populateData(item));
      this.$bus.$on('formHide', () => this.show = false);
      // _.debounce is a function provided by lodash to limit how
      // often a particularly expensive operation can be run.
      // In this case, we want to limit how often we access
      // the server, waiting until the user has completely
      // finished typing before making the ajax request. To learn
      // more about the _.debounce function (and its cousin
      // _.throttle), visit: https://lodash.com/docs#debounce
      // this.debouncedFormCode = _.debounce(this.autoComplete, 500);
      this.autoCompleteBroker();
      this.autoCompleteTreasury();

    },
    computed: {
      // a computed getter para atualizar automaticamente o total
      total: {
        get: function() {
          // `this` points to the vm instance
          return (parseFloat(this.form.price) * parseFloat(this.form.quant)) + parseFloat(this.form.broker_fee);
        },
        set: function(newValue) {

        }
      },
    },
    methods: {
      onSubmit(evt) {
        evt.preventDefault();
        if (!this.editMode) {
          this.form.post('/treasuries/investstore')
            .then(data => {
              console.log('promise success ' + data);
              this.tipBroker = 'Procurar corretora';
              this.tipCode = 'Procurar título';
              if (this.Slug2 !== 'create') {

                this.show = false;
                this.$bus.$emit('updateindexinvestedit', this.form);
                this.$bus.$emit('enlargeclose');
              } else {}
            })
            .catch(errors => console.log('promise error' + errors));
        } else {
          this.form.patch('/treasuries/invests/' + this.form.id)
            .then(data => {
              if (this.Slug2 !== 'create') {
                this.show = false;
                this.$bus.$emit('updateindexinvestedit', this.form);
                this.$bus.$emit('enlargeclose');
              } else {}
            })
            .catch(errors => {
              console.log('promise update fail');
            });
        }
      },
      onReset(evt) {
        evt.preventDefault();
        if (!this.editMode) {
          //this.form.reset();
          this.formReset();
          this.form.signal = 'buy';
          this.tipBroker = 'Procurar corretora';
          this.tipCode = 'Procurar título';
          /* Reset our form values */
          /* Trick to reset/clear native browser form validation state */
          // this.show = false;
          this.$bus.$emit('enlargeclose');
        } else {
          // this.show = false;
          this.$bus.$emit('enlargeclose');
        }
      },
      formReset() {
        this.form.reset();
      },
      //popula data dentro do proprio formulario
      populateData(value) {
        if (value) {
          console.log('populate acionado');
          this.editMode = true;
          for (let k in value) {
            if (typeof value[k] !== 'function') {
              Vue.set(this.form, [k], value[k]);
            }
          }
          this.show = true;
        } else {
          this.form.reset();
          this.form.signal = 'buy';
          this.tipBroker = 'Procurar corretora';
          this.tipCode = 'Procurar título';
          this.editMode = false;
          this.show = true;
        }
      },
      //busca as corretoras
      autoCompleteBroker() {
        axios.get('/api/brokers', {})
          .then(response => {
            this.resultbrokers = response.data;
          })
          .catch(error => {
            console.log('buscou corretoras');
            console.log(error.response);
          });
      },
      //busca titulos
      autoCompleteTreasury() {
        axios.get('/api/treasuries', {})
          .then(response => {
            this.resulttreasuries = response.data;
          })
          .catch(error => {
            console.log('buscou titulos');
            console.log(error.response);
          });
      },
      //busca os titulos
      //     autoComplete() {
      //       this.tipCode = 'Procurando...';
      //       axios.get('/api/searchtreasuries', {
      //           params: {
      //             query: this.form.code
      //           }
      //         }).then(response => {
      //           this.$nextTick(function() {
      //             this.results = response.data;
      //             this.tipCode = 'Ok';
      //             console.log("buscou" + response.data);
      //           });
      //         })
      //         .catch(error => {
      //           console.log(error.response);
      //         });
      //     },
    },
    directives: {
      twodecimals: {
        bind(el, arg) {
          if (el.value === "") {
            el.value = 0.00
          }
          el.value = racaz.numberForm.format(el.value.replace(",", "."));
          //el.value = Number(Math.round(parseFloat(el.value.replace(",", ".")) + 'e2') + 'e-2');
          el.addEventListener('keyup', () => {
            //el.value = el.value.replace(/[^0-9$.,]/g, '').replace(/(\..*)\./g, '$1').replace(/(?!^)-/g, '');
            var output = el.value.replace(/[^0-9.,]/g, '').replace(/(\..*)\./g, '').replace(/(?!^)-/g, '').split(/[.,]/g);
            el.value = output.shift() + (output.length ? ',' : '') + output.join('');
          });
          el.addEventListener('blur', () => {
            el.value = racaz.numberForm.format(el.value.replace(",", "."));
            //el.value = Number(Math.round(parseFloat(el.value.replace(",", ".")) + 'e2') + 'e-2');
          });
        }
      },
    },
    filters: {
      twodez: function(value) {
        if (!value) return '';
        value = value.toString().replace(/[^0-9.,]/g, '').replace(/(\..*)\./g, '').replace(/(?!^)-/g, '').split(/[.,]/g);
        return racaz.numberForm.format(value.replace(",", "."));
      }
    }
  }
</script>
