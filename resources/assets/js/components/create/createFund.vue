<template>
<!-- <b-modal ref="modalEditRow" size="lg" hide-footer title="" v-model="this.show" no-close-on-backdrop no-close-on-esc hide-header-close> -->
<div class="container-fluid justify-content-center" v-if="this.show">
  <b-alert variant="danger" dismissible :show="form.errors.any()" @dismissed="showDismissibleAlert=false">
    <div v-for="(value, key, index) in form.errors.errors">
      {{ index + 1 }}. {{ this.racaz.columnName(key) }}: {{ value[0] }}
    </div>
  </b-alert>
  <!--     <b-form @submit="onSubmit" @reset="onReset"> -->
  <b-form @submit="onSubmit" @reset="onReset" @click="form.errors.clear($event.target.name)">
    <b-col>
      <b-form-group horizontal breakpoint="sm" label-cols="2" :label="this.Slug" label-size="lg" label-class="font-weight-bold pt-0" class="mb-0">
        <!--           cnpj -->
        <b-form-group id="cnpjlabel" label="CNPJ" label-for="cnpj">
<!--           <b-form-input v-mask="['###.###.###-##', '##.###.###/####-##']" v-model="form.cnpj" ref="cnpj" id="cnpj" :class=="{ 'is-invalid': form.errors.has(cnpj) }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira esta informação.')" required -->
          <b-form-input v-mask="'##.###.###/####-##'" v-model="form.cnpj" ref="cnpj" id="cnpj" :class="{ 'is-invalid': form.errors.has('cnpj') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o CNPJ.')" required
            dusk="cnpj">
          </b-form-input>
          <p class="text-danger" v-if="form.errors.has('cnpj')" v-text="form.errors.get('cnpj')">
          </p>

          <b-button variant="primary" @click="getFunds(form.cnpj)">{{ 'Buscar fundo' }}</b-button>

        </b-form-group>
        <!--           name  -->
        <b-form-group id="namelabel" label="Nome" label-for="name">
          <b-form-input v-model="form.name" :disabled="form.name === '' && form.id === ''" ref="name" id="name" :class="{ 'is-invalid': form.errors.has('name') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o nome.')" required dusk="name">
          </b-form-input>
          <p class="text-danger" v-if="form.errors.has('name')" v-text="form.errors.get('name')">
          </p>
        </b-form-group>
        <!--           data de registro -->
        <b-form-group id="reg_datelabel" label="Data de registro" label-for="reg_date">
             
          <datepicker id="reg_date" name="reg_date" v-model="form.reg_date" :disabled="form.name === '' && form.id === ''" autocomplete="off" input-class="form-control" :class="{ 'is-invalid': form.errors.has('reg_date') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira a data de registro.')"
            placeholder="Clique aqui para inserir a data." format="dd/MM/yyyy" required dusk="datepicker">
          </datepicker>
        
        
<!--           <b-form-input type="date" v-model="form.reg_date" ref="reg_date" id="reg_date" :class=="{ 'is-invalid': form.errors.has(reg_date) }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira esta informação.')" required dusk="reg_date">
          </b-form-input> -->
          
          <p class="text-danger" v-if="form.errors.has('reg_date')" v-text="form.errors.get('reg_date')">
          </p>

        </b-form-group>
        <!--           data de constituicao -->
        <b-form-group id="const_datelabel" label="Data de constituição" label-for="const_date">
          
                    <datepicker id="const_date" name="const_date" v-model="form.const_date" :disabled="form.name === '' && form.id === ''" autocomplete="off" input-class="form-control" :class="{ 'is-invalid': form.errors.has('const_date') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira a data de constituição.')"
            placeholder="Clique aqui para inserir a data." format="dd/MM/yyyy" required dusk="datepicker">
          </datepicker>
          
<!--           <b-form-input type="date" v-model="form.const_date" ref="const_date" id="const_date" :class=="{ 'is-invalid': form.errors.has(const_date) }" oninput="setCustomValidity('')" oninvalid="this.setCustomValidity('Insira esta informação.')" required dusk="const_date">
          </b-form-input> -->
          <p class="text-danger" v-if="form.errors.has('const_date')" v-text="form.errors.get('const_date')">
          </p>

        </b-form-group>

        <!--           data de cancelamento -->
        <b-form-group id="canc_datelabel" label="Data de cancelamento" label-for="canc_date">
          
                              <datepicker id="canc_date" name="canc_date" v-model="form.canc_date" :disabled="form.name === '' && form.id === ''" autocomplete="off" input-class="form-control" :class="{ 'is-invalid': form.errors.has('canc_date') }" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira a data de cancelamento.')"
            placeholder="Clique aqui para inserir a data." format="dd/MM/yyyy"  dusk="datepicker">
          </datepicker>
          
<!--           <b-form-input type="date" v-model="form.canc_date" ref="canc_date" id="canc_date" :class=="{ 'is-invalid': form.errors.has(canc_date) }" dusk="canc_date">
          </b-form-input> -->
          
          <p class="text-danger" v-if="form.errors.has('canc_date')" v-text="form.errors.get('canc_date')">
          </p>

        </b-form-group>

        <!--           situacao -->
        <b-form-group id="sitlabel" label="Situação" label-for="sit">
          <b-form-input v-model="form.sit" ref="sit" id="sit" :class="{ 'is-invalid': form.errors.has('sit') }" :disabled="form.name === '' && form.id === ''" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira esta informação.')" required dusk="sit">
          </b-form-input>
          <p class="text-danger" v-if="form.errors.has('sit')" v-text="form.errors.get('sit')">
          </p>
        </b-form-group>

        <!--             classe -->
        <b-form-group id="classelabel" label="Classe" label-for="classe">
          <b-form-input v-model="form.classe" ref="classe" id="classe" :class="{ 'is-invalid': form.errors.has('classe') }" :disabled="form.name === '' && form.id === ''" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira esta informação.')" required dusk="classe">
          </b-form-input>
          <p class="text-danger" v-if="form.errors.has('classe')" v-text="form.errors.get('classe')">
          </p>
        </b-form-group>
        <!--           rentabilidade -->
        <b-form-group id="rentabilidadelabel" label="Rentabilidade" label-for="rentabilidade">
          <b-form-input v-model="form.rentabilidade" ref="rentabilidade" id="rentabilidade" :class="{ 'is-invalid': form.errors.has('rentabilidade') }" :disabled="form.name === '' && form.id === ''" dusk="rentabilidade">
          </b-form-input>
          <p class="text-danger" v-if="form.errors.has('rentabilidade')" v-text="form.errors.get('rentabilidade')">
          </p>
        </b-form-group>
        <!--           investidor qualificado -->
        <b-form-group id="inv_quallabel" label="Investidor qualificado" label-for="inv_qual">
          <b-form-select v-model="form.inv_qual" ref="inv_qual" id="inv_qual" :class="{ 'is-invalid': form.errors.has('inv_qual') }" :disabled="form.name === '' && form.id === ''" dusk="inv_qual">
            <option value="" disabled>Por favor, escolha um.</option>
            <option value="0">Não</option>
            <option value="1">Sim</option>
          </b-form-select>
          <p class="text-danger" v-if="form.errors.has('inv_qual')" v-text="form.errors.get('inv_qual')">
          </p>
        </b-form-group>
        <!--           fundo exclusivo -->
        <b-form-group id="fundo_exclabel" label="Fundo exclusivo" label-for="fundo_exc">
          <b-form-select v-model="form.fundo_exc" ref="fundo_exc" id="fundo_exc" :class="{ 'is-invalid': form.errors.has('fundo_exc') }" :disabled="form.name === '' && form.id === ''" dusk="fundo_exc">
            <option value="" disabled>Por favor, escolha um.</option>
            <option value="0">Não</option>
            <option value="1">Sim</option>
          </b-form-select>
          <p class="text-danger" v-if="form.errors.has('fundo_exc')" v-text="form.errors.get('fundo_exc')">
          </p>
        </b-form-group>

        <!--           fundo de cotas -->
        <b-form-group id="fundo_cotaslabel" label="Fundo de cotas" label-for="fundo_cotas">
          <b-form-select v-model="form.fundo_cotas" ref="fundo_cotas" id="fundo_cotas" :class="{ 'is-invalid': form.errors.has('fundo_cotas') }" :disabled="form.name === '' && form.id === ''" dusk="fundo_cotas">
            <option value="" disabled>Por favor, escolha um.</option>
            <option value="0">Não</option>
            <option value="1">Sim</option>
          </b-form-select>
          <p class="text-danger" v-if="form.errors.has('fundo_cotas')" v-text="form.errors.get('fundo_cotas')">
          </p>
        </b-form-group>
        <!--           ir -->
        <b-form-group id="irlabel" label="Incide IR" label-for="ir">
          <b-form-select v-model="form.ir" ref="ir" id="ir" :class="{ 'is-invalid': form.errors.has('ir') }" :disabled="form.name === '' && form.id === ''" dusk="ir">
            <option value="" disabled>Por favor, escolha um.</option>
            <option value="0">Não</option>
            <option value="1">Sim</option>
          </b-form-select>
          <p class="text-danger" v-if="form.errors.has('ir')" v-text="form.errors.get('ir')">
          </p>
        </b-form-group>
        <!--           taxa de performance -->
        <b-form-group id="taxa_perflabel" label="Taxa de performance" label-for="taxa_perf">
          <b-form-input v-model="form.taxa_perf" ref="taxa_perf" id="taxa_perf" :class="{ 'is-invalid': form.errors.has('taxa_perf') }" :disabled="form.name === '' && form.id === ''" dusk="taxa_perf">
          </b-form-input>
          <p class="text-danger" v-if="form.errors.has('taxa_perf')" v-text="form.errors.get('taxa_perf')">
          </p>
        </b-form-group>

        <!--           diretor -->
        <b-form-group id="diretorlabel" label="Diretor" label-for="diretor">
          <b-form-input v-model="form.diretor" ref="diretor" id="diretor" :class="{ 'is-invalid': form.errors.has('diretor') }" :disabled="form.name === '' && form.id === ''" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o diretor.')" required dusk="diretor">
          </b-form-input>
          <p class="text-danger" v-if="form.errors.has('diretor')" v-text="form.errors.get('diretor')">
          </p>
        </b-form-group>

        <!--           administrador -->
        <b-form-group id="adminlabel" label="Administrador" label-for="admin">
          <b-form-input v-model="form.admin" ref="admin" id="admin" :class="{ 'is-invalid': form.errors.has('admin') }" :disabled="form.name === '' && form.id === ''" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o administrador.')" required dusk="admin">
          </b-form-input>
          <p class="text-danger" v-if="form.errors.has('admin')" v-text="form.errors.get('admin')">
          </p>
        </b-form-group>

        <!--           gestor -->
        <b-form-group id="gestorlabel" label="Gestor" label-for="gestor">
          <b-form-input v-model="form.gestor" ref="gestor" id="gestor" :class="{ 'is-invalid': form.errors.has('gestor') }" :disabled="form.name === '' && form.id === ''" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o gestor.')" required dusk="gestor">
          </b-form-input>
          <p class="text-danger" v-if="form.errors.has('gestor')" v-text="form.errors.get('gestor')">
          </p>
        </b-form-group>

        <!--           auditor -->
        <b-form-group id="auditorlabel" label="Auditor" label-for="auditor">
          <b-form-input v-model="form.auditor" ref="auditor" id="auditor" :class="{ 'is-invalid': form.errors.has('auditor') }" :disabled="form.name === '' && form.id === ''" oninput="setCustomValidity(''); checkValidity(); setCustomValidity(validity.valid ? '' :'Insira o auditor.')" required dusk="auditor">
          </b-form-input>
          <p class="text-danger" v-if="form.errors.has('auditor')" v-text="form.errors.get('auditor')">
          </p>
        </b-form-group>


      </b-form-group>
    </b-col>
    <b-row align-h="end">
      <b-col md="6" offset-md="1">
        <b-button type="submit" variant="success" :disabled="form.errors.any() || !fetched" dusk="createsubmit">{{ editMode ? 'Atualizar' : 'Inserir' }}</b-button>
        <b-button type="reset" variant="danger" dusk="createreset" v-if="this.Slug2 !== 'create'">{{ 'Fechar' }}</b-button>
        <b-button variant="primary" @click="formReset" dusk="createformreset">{{ 'Limpar' }}</b-button>
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
} from './../../javascript/classes.js';

  import Datepicker from 'vuejs-datepicker';

export default {
    components: {
    Datepicker
  },
  data() {
    return {
      form: new Form({
        id: '',
        name: '',
        cnpj: '',
        reg_date: '',
        const_date: '',
        canc_date: '',
        sit: '',
        classe: '',
        rentabilidade: '',
        inv_qual: '',
        fundo_exc: '',
        fundo_cotas: '',
        ir: '',
        taxa_perf: '',
        diretor: '',
        admin: '',
        gestor: '',
        auditor: '',
      }),
      show: false,
      slug: racaz.slug,
      Slug: racaz.columnName(racaz.pathArray[1]),
      Slug2: racaz.columnName(racaz.pathArray[2]),
      pathArray: racaz.pathArray,
      editMode: false,
      fetched: false,
      newFund: {
        name: '',
        cnpj: '',
        reg_date: '',
        const_date: '',
        canc_date: '',
        sit: '',
        classe: '',
        rentabilidade: '',
        inv_qual: '',
        fundo_exc: '',
        fundo_cotas: '',
        ir: '',
        taxa_perf: '',
        diretor: '',
        admin: '',
        gestor: '',
        auditor: '',
      }
    }
  },
  created: function() {
    window.events.$on('createFund', (item) => this.populateData(item));
    if (this.data) {
      this.populateData(this.data);
    } else {
      this.populateData();
    }
  },
  computed: {

  },
  methods: {
    //     updateData(key) {
    //       Vue.set(this.form, [key], this.$refs[key][0].localValue);
    //       //let field = document.getElementById(key);
    //       //field.setCustomValidity('');
    //       if (key == 'coupon') {
    //       this.hasCoupon = this.$refs[key][0].localValue;
    //       console.log('chamou updateDataSelect');
    //       }
    //     },
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
        //this.form.reset();
        this.formReset();
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
    populateData(value) {
      if (value) {
        this.fetched = true;
        if (!value.id) {
        this.editMode = false;
        for (let k in value) {
          if (typeof value[k] !== 'function') {
            Vue.set(this.form, [k], value[k]);
          }
        }          
        this.show = true;
        } else {
        this.editMode = true;
        console.log('populate chamado');
        for (let k in value) {
          if (typeof value[k] !== 'function') {
            Vue.set(this.form, [k], value[k]);
          }
        }        
        this.show = true;
        }
//         this.editMode = true;
//         console.log('populate chamado');
//         for (let k in value) {
//           if (typeof value[k] !== 'function') {
//             Vue.set(this.form, [k], value[k]);
//           }
//         }
//         this.show = true;
      } else {
        this.fetched = false;
        this.editMode = false;
        this.form.reset();
        //         axios.get('/api/columns/' + this.pathArray[1])
        //           .then(response => {
        //             response.data.forEach(value => {
        //               Vue.set(this.form, [value], '');
        //             });
        //           })
        //           .catch(error => {
        //             console.log(error);
        //           });
        this.show = true;
      }
    },
    async getFunds(cnpj) {
      if (cnpj.length !== 18){
        this.$nextTick(function () {
        this.form.onFail({"errors":{"cnpj": ["O código do fundo deve ter no minimo 18 caracteres."]}});
      })
      } else {
      console.log(cnpj);
      let fund = await fundsInfo.get(cnpj)
      .catch(error => {
        flash('Não foi encontrado registro com este CNPJ ou não foi possível buscar os dados.|warning')
        console.log(fund)
      })
      console.log(fund)
      if (Array.isArray(fund)) {
        console.log('podemos popular')
        this.newFund.name = racaz.removeAcento(racaz.cvmStringReplace(fund[0].DENOM_SOCIAL.toString()));
        this.newFund.cnpj = fund[0].CNPJ_FUNDO ;
        this.newFund.reg_date = fund[0].DT_REG ;
        this.newFund.const_date = fund[0].DT_CONST ;
        this.newFund.canc_date = fund[0].DT_CANCEL ;
        this.newFund.sit = racaz.removeAcento(racaz.cvmStringReplace(fund[0].SIT.toString()));
        this.newFund.classe = racaz.removeAcento(racaz.cvmStringReplace(fund[0].CLASSE.toString()));
        this.newFund.rentabilidade = racaz.removeAcento(racaz.cvmStringReplace(fund[0].RENTAB_FUNDO.toString()));
        
        this.newFund.fundo_cotas = fund[0].FUNDO_COTAS === 'S' ? 1 : 0;
        this.newFund.fundo_exc = fund[0].FUNDO_EXCLUSIVO === 'S' ? 1 : 0;
        this.newFund.inv_qual = fund[0].INVEST_QUALIF === 'S' ? 1 : 0;
        this.newFund.ir = fund[0].TRIB_LPRAZO === 'S' ? 1 : 0;
        
//         fund[0].FUNDO_COTAS === 'S' ? this.newFund.fundo_cotas = 1 : this.newFund.fundo_cotas = 0;
//         fund[0].FUNDO_EXCLUSIVO === 'S' ? this.newFund.fundo_exc = 1 : this.newFund.fundo_exc = 0;
//         fund[0].INVEST_QUALIF === 'S' ? this.newFund.inv_qual = 1 : this.newFund.inv_qual = 0;
//         fund[0].FUNDO_COTAS === 'S' ? this.newFund.fundo_cotas = 1 : this.newFund.fundo_cotas = 0;

        // this.newFund.inv_qual = fund[0].INVEST_QUALIF ;
        // this.newFund.fundo_exc = fund[0].FUNDO_EXCLUSIVO ;
        // this.newFund.fundo_cotas = fund[0].FUNDO_COTAS ;
        //this.newFund.ir = fund[0].TRIB_LPRAZO ;
        this.newFund.taxa_perf = typeof variable !== 'undefined' ? racaz.removeAcento(racaz.cvmStringReplace(fund[0].TAXA_PERFM.toString())) : '';
        this.newFund.diretor = racaz.removeAcento(racaz.cvmStringReplace(fund[0].DIRETOR.toString()));
        this.newFund.admin = racaz.removeAcento(racaz.cvmStringReplace(fund[0].ADMIN.toString()));
        this.newFund.gestor = racaz.removeAcento(racaz.cvmStringReplace(fund[0].GESTOR.toString()));
        this.newFund.auditor = racaz.removeAcento(racaz.cvmStringReplace(fund[0].AUDITOR.toString()));
        this.fetched = true;
        enlarge('createFund',this.newFund);
      } else {
        flash('Não foi encontrado registro com este CNPJ ou não foi possível buscar os dados.|warning')
        console.log(fund)
      }
    }
  },
  }
}
</script>
