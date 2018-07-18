<template>
<b-card :header="this.headerTitle" style="margin: auto; max-width: 80%;">
  <slot></slot>
</b-card>
</template>
<script>
export default {
  props: ['passed'],
  data() {
    return {
      slug: racaz.slug,
      //slug2: racaz.slug2,
      Slug: racaz.columnName(racaz.pathArray[1]),
      Slug2: racaz.columnName(racaz.pathArray[2]),
      headerTitle: '',
    }
  },
  mounted: function() {
    //chama os componentes para criar algo novo, do zero
    if (this.slug === 'invests' && this.Slug2 === 'create') {
      console.log('invests');
      createTypeStocks();
      createTypeTreasuries();
      createTypeSecurities();
    } else if (this.slug !== 'invests' && this.slug !== 'funds' && this.Slug2 === 'create') {
    //caso seja usado para criar algum elemento sem modal, no caminho de http
      create();
      console.log('not invests');
      //create();
    } else if (this.slug === 'funds' && this.Slug2 === 'create') {
      createFund();     
    }
  }
}
</script>
