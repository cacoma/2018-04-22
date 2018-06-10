<template>
<div>
  <b-navbar-nav>
    <b-nav-item href="/home" dusk="navbaradminhome">Home</b-nav-item>
    <b-nav-item-dropdown text="Investimentos" right dusk="navbaradmininvestimentos">
      <b-dropdown-item href="/invests" dusk="navbaradmininvestimentosindexar">Indexar</b-dropdown-item>
      <b-dropdown-item href="/consolidated" dusk="navbaradmininvestimentosconsolidated">Consolidado</b-dropdown-item>
      <b-dropdown-item href="/invests/create" dusk="navbaradmininvestimentosadicionar">Adicionar</b-dropdown-item>
    </b-nav-item-dropdown>
    <b-nav-item-dropdown text="Cadastros" right v-if="this.auth.role_id === 1">
      <b-dropdown-item href="/stocks">Ações</b-dropdown-item>
      <b-dropdown-item href="/brokers">Corretoras</b-dropdown-item>
      <b-dropdown-item href="/users">Usuários</b-dropdown-item>
      <b-dropdown-item href="/treasuries">Títulos do tesouro</b-dropdown-item>
    </b-nav-item-dropdown>
    <b-nav-item-dropdown text="Cotações" right v-if="this.auth.role_id === 1">
      <b-dropdown-item href="/dailyquotes">Diárias</b-dropdown-item>
      <b-dropdown-item href="/monthlyquotes">Mensais</b-dropdown-item>
    </b-nav-item-dropdown>
  </b-navbar-nav>
</div>
</template>

<script>
export default {
  props: ['auth'],
  data() {
    return {
    }
  },
 }
</script>
