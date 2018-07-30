<template>
<div class="container-fluid justify-content-center" v-if="this.show">
  <b-alert variant="danger" dismissible :show="this.form.errors.any()" @dismissed="showDismissibleAlert=false">
    <div v-for="(value, key, index) in this.form.errors.errors">
      {{ index + 1 }}. {{ this.racaz.columnName(key) }}: {{ value[0] }}
    </div>
  </b-alert>
  <b-form @submit="onSubmit" @reset="onReset" @input="form.errors.clear($event.target.name)" id="createtypesecurityform" dusk="createtypesecurityform" autocomplete="off">
    <input autocomplete="false" name="hidden" type="text" style="display:none;">
    <!-- <b-form-row v-if="!this.editMode">
      <b-col>
        <b-form-group label="Operação de: ">
          <b-form-radio-group id="signal" name="signal" buttons button-variant="outline-primary" size="md" v-model="form.signal" :options="optionsSignal" v-bind:class="{ 'is-invalid': form.errors.has('signal') }" required/>
        </b-form-group>
        <p class="text-danger" v-if="form.errors.has('signal')" v-text="form.errors.get('signal')">
        </p>
      </b-col>
    </b-form-row> -->
    <b-form-row>
      <b-col>
        <b-form-group id="namelabel" label="Código do titulo:" label-for="name">
          <b-tooltip ref="tooltipName" v-show="tipName" target="name" placement="topright">
            <strong v-text="tipName"></strong>
          </b-tooltip>
          <input type="text" list="listsecurity" placeholder="Código da título" v-model="form.name" autocomplete="off" class="form-control" v-bind:class="{ 'is-invalid': form.errors.has('name') }" name="name" id="name" ref="name" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o security')"
            required>
          <datalist id="listsecurity">
                   <option v-for="resultssecurity in resultssecurities" v-bind:value="resultssecurity">{{ resultssecurity }}</option>
                </datalist>
          <p class="text-danger" v-if="form.errors.has('name')" v-text="form.errors.get('name')">
          </p>

          </b-tooltip>
        </b-form-group>
      </b-col>
      <b-col>
        <b-form-group id="datelabel" label="Data do investimento" label-for="date_invest">
          <datepicker id="date_invest" name="date_invest" v-model="form.date_invest" autocomplete="nope" input-class="form-control" v-bind:class="{ 'is-invalid': form.errors.has('date_invest') }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira a data do investimento.')"
            placeholder="Clique aqui para inserir a data." format="dd/MM/yyyy" :disabledDates="this.disabledDates" required dusk="datepicker">
          </datepicker>
          <!--           <b-form-input type="date" id="date_invest" name="date_invest" v-model="form.date_invest" input-class="form-control" v-bind:class="{ 'is-invalid': form.errors.has('date_invest') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira a data do investimento.')"
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
      <b-col>
        <b-form-group id="issuerlabel" label="Emissor:" label-for="issuer_name">
          <b-tooltip ref="tooltipIssuer" target="issuer_name" v-show="tipIssuer" placement="topright">
            <strong v-text="tipIssuer"></strong>
          </b-tooltip>
          <input type="text" list="listissuers" placeholder="Emissor" v-model="form.issuer_name" autocomplete="off" class="form-control" v-bind:class="{ 'is-invalid': form.errors.has('issuer_name') }" name="issuer_name" id="issuer_name" ref="issuer_name" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o emissor.')"
            required>
          <datalist id="listissuers">
                   <option v-for="resultissuer in resultissuers" v-bind:value="resultissuer" class="text-light bg-dark">{{resultissuer}}</option>
            </datalist>
          <p class="text-danger" v-if="form.errors.has('issuer_name')" v-text="form.errors.get('issuer_name')">
          </p>
        </b-form-group>
      </b-col>
    </b-form-row>
    <!--   segunda linha -->
    <b-form-row>
      <b-col>
        <b-form-group id="pricelabel" label="Valor do titulo" label-for="price">
          <b-input-group prepend="R$">
            <quantinput id="price" name="price" v-model="form.price" v-bind="quantInputReais" class="form-input input-lg form-control" v-bind:class="{ 'is-invalid': form.errors.has('price') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o preco')"
              required>
            </quantinput>
          </b-input-group>
          <p class="text-danger" v-if="form.errors.has('price')" v-text="form.errors.get('price')">
          </p>
        </b-form-group>
      </b-col>
      <!-- <b-col>
        <b-form-group id="quantlabel" label="Quantidade:" label-for="quant">
          <quantinput id="quant" name="quant" v-model="form.quant" v-bind="quantInputReais" class="form-input input-lg form-control" v-bind:class="{ 'is-invalid': form.errors.has('quant') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira a quantidade.')"
              required>
            </quantinput>
          </b-input-group>
          <p class="text-danger" v-if="form.errors.has('quant')" v-text="form.errors.get('quant')">
          </p>
        </b-form-group>
      </b-col> -->
      <b-col>
        <b-form-group id="broker_feelabel" label="Corretagem:" label-for="broker_fee">
          <b-input-group prepend="R$">
            <!--             <money id="broker_fee" name="broker_fee" v-model="form.broker_fee" v-bind="money" class="form-input input-lg form-control" v-bind:class="{ 'is-invalid': form.errors.has('broker_fee') }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira o valor da corretagem.')"
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
            <!--             <money id="rate" name="rate" v-model="form.rate" v-bind="money" class="form-input input-lg form-control"  v-bind:class="{ 'is-invalid': form.errors.has('rate') }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira a taxa de retorno.')" required>
            </money>              -->
            <quantinput id="rate" name="rate" v-model="form.rate" v-bind="quantInputPercent" class="form-input input-lg form-control" v-bind:class="{ 'is-invalid': form.errors.has('rate') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira a taxa de retorno.')"
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
            <!--             <money id="total" :value="total" v-bind="money" class="form-input input-lg form-control" disabled dusk="createtypesecuritytotal">
            </money>             -->
            <quantinput id="total" :value="total" v-bind="quantInputReais" class="form-input input-lg form-control" disabled dusk="createtypetreasurytotal">
            </quantinput>
          </b-input-group>
        </b-form-group>
      </b-col>
    </b-form-row>
    <b-row align-h="end">
      <b-col md="6" offset-md="1">
        <b-button type="submit" variant="success" :disabled="form.errors.any()" dusk="createtypesecurityubmit"> {{ this.editMode ? 'Atualizar' : 'Inserir' }}</b-button>
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
        issuer_name: '',
        price: '',
        quant: '',
        rate: '',
        broker_fee: '',
      }),
      quantInput: {
        precision: 0,
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
      resultissuers: [],
      resultsecurities: [],
      show: false,
      disabledDates: racaz.dateInvestLimit.disabledDates,
      tipBroker: 'Procurar corretora',
      tipName: 'Procurar título',
      tipIssuer: 'Procurar emissor',
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
    // whenever name changes, this function will run
    //     'form.name': function(newname, oldname) {
    //       this.debouncedFormname();
    //     },
  },
  created: function() {
    window.events.$on('createTypeSecurities', (item) => this.populateData(item));
    this.$bus.$on('formHide', () => this.show = false);
    // _.debounce is a function provided by lodash to limit how
    // often a particularly expensive operation can be run.
    // In this case, we want to limit how often we access
    // the server, waiting until the user has completely
    // finished typing before making the ajax request. To learn
    // more about the _.debounce function (and its cousin
    // _.throttle), visit: https://lodash.com/docs#debounce
    //     this.debouncedFormname = _.debounce(this.autoComplete, 500);
    this.autoCompleteBroker();
    this.autoCompleteIssuer();
    this.autoCompleteSecurity();

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
        this.form.post('/securities/investstore')
          .then(data => {
            console.log('promise success ' + data);
            this.tipBroker = 'Procurar corretora';
            this.tipName = 'Procurar título';
            this.tipIssuer = 'Procurar emissor';
            if (this.Slug2 !== 'create') {

              this.show = false;
              this.$bus.$emit('updateindexinvestedit', this.form);
              this.$bus.$emit('enlargeclose');
            } else {}
          })
          .catch(errors => console.log('promise error' + errors));
      } else {
        this.form.patch('/securities/invests/' + this.form.id)
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
        this.form.quant = 1;
        this.tipBroker = 'Procurar corretora';
        this.tipName = 'Procurar título';
        this.tipIssuer = 'Procurar emissor';
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
        this.form.quant = 1;
        this.form.signal = 'buy';
        this.tipBroker = 'Procurar corretora';
        this.tipName = 'Procurar título';
        this.tipIssuer = 'Procurar emissor';
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
    //busca os emissores
    autoCompleteIssuer() {
      axios.get('/api/issuers', {})
        .then(response => {
          this.resultissuers = response.data;
        })
        .catch(error => {
          console.log('buscou emissores');
          console.log(error.response);
        });
    },
    //busca os securities
    autoCompleteSecurity() {
      axios.get('/api/securities', {})
        .then(response => {
          this.resultssecurities = response.data;
        })
        .catch(error => {
          console.log('buscou securities');
          console.log(error.response);
        });
    },
    //busca os titulos
    //     autoComplete() {
    //       this.tipName = 'Procurando...';
    //       axios.get('/api/searchsecurities', {
    //           params: {
    //             query: this.form.name
    //           }
    //         }).then(response => {
    //           this.$nextTick(function() {
    //             this.results = response.data;
    //             this.tipName = 'Ok';
    //             console.log("buscou" + response.data);
    //           });
    //         })
    //         .catch(error => {
    //           console.log(error.response);
    //         });
    //     },
  },
  directives: {
    //     twodecimals: {
    //       bind(el, arg) {
    //         if (el.value === "")
    //           {
    //             el.value = 0.00
    //           }
    //         el.value = racaz.numberForm.format(el.value.replace(",", "."));
    //         //el.value = Number(Math.round(parseFloat(el.value.replace(",", ".")) + 'e2') + 'e-2');
    //         el.addEventListener('keyup', () => {
    //           //el.value = el.value.replace(/[^0-9$.,]/g, '').replace(/(\..*)\./g, '$1').replace(/(?!^)-/g, '');
    //           var output = el.value.replace(/[^0-9.,]/g, '').replace(/(\..*)\./g, '').replace(/(?!^)-/g, '').split(/[.,]/g);
    //           el.value = output.shift() + (output.length ? ',' : '') + output.join('');
    //         });
    //         el.addEventListener('blur', () => {
    //           el.value = racaz.numberForm.format(el.value.replace(",", "."));
    //           //el.value = Number(Math.round(parseFloat(el.value.replace(",", ".")) + 'e2') + 'e-2');
    //         });
    //       }
    //     },
  },
  filters: {
    //   twodez: function (value) {
    //     if (!value) return '';
    //     value = value.toString().replace(/[^0-9.,]/g, '').replace(/(\..*)\./g, '').replace(/(?!^)-/g, '').split(/[.,]/g);
    //     return racaz.numberForm.format(value.replace(",", "."));
    //   }
  }
}
</script>
