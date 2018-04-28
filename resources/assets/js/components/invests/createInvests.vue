<template>
<div class="container-fluid justify-content-center">
<div class="card">
  <div class="card-header">Adicionar investimento</div>
    <div class="card-body">
     <form method="post" action="">
          <div class="form-row">
            <div class="form-group col-md-12">
              <label class="control-label" for="type">Tipo de investimento:</label>
              <select v-model="type" name="type" class="form-control" required>
                <option selected value="">Selecionar</option>
                <option value="A">Acoes</option>
                <option value="B">Nao usar</option>
                <option value="C">Nao usar tambem</option>
              </select>
     
              </div>
            </div>
       </form>
            
            <createinveststypestocks v-if="type === 'A'">
                
            </createinveststypestocks>
            <template v-else-if="type === 'B'">
            <form method="post" action="">
            <div class="form-row">
              <div class="form-group col-md-12">
                          <label class="control-label col-md-6" for="nu">Nao usar</label>
                        </div>
            </div>
            </form>
            </template>
            <template v-else-if="type === 'C'">
              <form method="post" action="">
              <div class="form-row">
              <div class="form-group col-md-12">
                 <label class="control-label col-md-6" for="nut">Nao usar tambem</label>
                          </div> 
              </div>
              </form>
            </template>           
            <template v-else>
              <form method="post" action="">
              <div class="form-row">
              <div class="form-group col-md-12" >
              <label class="control-label col-md-6">Selecione uma opção acima.</label>
              </div>
              </div>
              </form>
            </template>

     </div> 
     </div> 
     </div> 
  </template>
  <script>
    export default {
        data() {
          return {
            type: "",
            errors: []
            }
          },
    }
</script>