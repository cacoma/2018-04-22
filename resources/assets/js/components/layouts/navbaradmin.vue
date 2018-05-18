<template>
<div>
  <b-navbar-nav>
    <b-nav-item href="/home">Home</b-nav-item>
    <b-nav-item-dropdown text="Investimentos" right>
      <b-dropdown-item href="/invests">Indexar</b-dropdown-item>
      <b-dropdown-item href="/invests/create">Adicionar</b-dropdown-item>
    </b-nav-item-dropdown>
    <b-nav-item-dropdown text="Cadastros" right v-if="this.auth.role_id == 1">
      <b-dropdown-item href="/stocks">Ações</b-dropdown-item>
      <b-dropdown-item href="/brokers">Corretoras</b-dropdown-item>
      <b-dropdown-item href="/users">Usuários</b-dropdown-item>
    </b-nav-item-dropdown>
    <b-nav-item-dropdown text="Cotações" right v-if="this.auth.role_id == 1">
      <b-dropdown-item href="/dailyquotes">Diárias</b-dropdown-item>
      <b-dropdown-item href="/monthlyquotes">Mensais</b-dropdown-item>
    </b-nav-item-dropdown>
  </b-navbar-nav>

<!--   <b-modal ref="sessionModal" id="sessionModal" hide-footer size="sm" centered v-model="this.showModal">
    <div class="d-block text-center">
      <h3 v-text="this.modalMessage"></h3>
    </div>
  </b-modal> -->
</div>
</template>

<script>
export default {
  props: ['auth', 'alerts'],
  data() {
    return {
      //showModal: false,
      modalMessage: ''
    }
  },
  created: function() {
  },
//   computed: {
//     // sempre que a pergunta mudar, essa função será executada
//     showModal: function () {
// //       this.answer = 'Esperando você parar de escrever...'
// //       this.getAnswer()
//       if (this.alerts.hasOwnProperty('errors')) {
//       this.modalMessage = this.alerts.errors;
//       return true;
//       }
//       if (sessionStorage.getItem("errors2")) {
//           // Restore the contents of the text field
//           this.modalMessage = sessionStorage.getItem("errors2");
//           sessionStorage.removeItem('errors2');
//           return true;
//         }
//     }
//   },
  methods: {},
}
</script>
