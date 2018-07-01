<template>
<div class="container-fluid justify-content-center">
<div class="card">
  <div class="card-header">Adicionar investimento</div>
    <div class="card-body">

  <button
    v-for="tab in tabs"
    v-bind:key="tab.name"
    v-bind:dusk="tab.name"
    v-bind:class="['tab-button', { active: currentTab.name === tab.name }]"
    v-on:click="currentTab = tab"
  >{{ tab.name }}</button>

  <component
    v-bind:is="currentTab.component"
    class="tab"
  ></component>

     </div>
     </div>
     </div>
     </div>
  </template>
  <script>
    var tabs = [
  {
    name: 'Ações ',
    component: {
      template: '<div><moldura><createinveststypestocks dusk="createinveststypestocks"></createinveststypestocks></moldura></div>'
    }
  },
  {
    name: 'Títulos do tesouro ',
    component: {
      template: '<div><moldura><createinveststypetreasuries dusk="createinveststypetreasuries"></createinveststypetreasuries> </moldura></div>'
    }
  },
  {
    name: 'Renda Fixa ',
    component: {
      template: '<div><moldura><createinveststypesecurities dusk="createinveststypesecurities"></createinveststypesecurities> </moldura></div>',
    }
  }
]

    export default {
  data() {
    return {
  	tabs: tabs,
    currentTab: tabs[0]
  }
  }
  }
</script>