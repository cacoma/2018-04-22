<template>
opa!
</template>
<script>
//const axios = require('axios');
//const puppeteer = require('puppeteer');
//const mysql = require('mysql');

export default {
  data() {
    return {
      
    }
  },
  mounted: function(){
  this.scrapeTreasury();
  },
   methods: {
     scrapeTreasury() {
    let scrape = async () => {
    //cria o browser
    const browser = await puppeteer.launch({
        headless: true
    });
    //cria uma pagina nova
    const page = await browser.newPage();
    //vai ate o site da receita
    await page.goto('http://www.tesouro.fazenda.gov.br/tesouro-direto-precos-e-taxas-dos-titulos', {
        waitUntil: 'domcontentloaded'
    });
    //espera 1000, ate carregar tudo
    //await page.waitFor(1000);
    console.log('carregou tudo');
    //await page.click('')
    const result = await page.evaluate(() => {


        let data = [];
        //let updatedTo = '';
      
        let updatedTo = document.querySelector('#p_p_id_precosetaxas_WAR_tesourodiretoportlet_ > div > div > div > b').innerText;


//         const databaseCheck = (date) => {
//                const trueOrNot =  axios.get('/api/treasurycheck/' + date);
//                return trueOrNot;
//                 };
        

//             async function checkTreasuries(updatedTo) {
//           try {
//             const response = await axios.get('/api/treasurycheck/' + updatedTo);
//             console.log(response);
//             return response;
//           } catch (error) {
//             console.error(error);
//           }
//         }
        //console.log(go);
        //if (!go){  
      
        let elements = document.querySelectorAll('.camposTesouroDireto');
        let i = 0;

        for (let element of elements) {
            // let nome = element.querySelector('.listing0').innerText;
            // let vencimento = element.querySelector('.listing').innerText;
            // let taxa = element.querySelector('.listing').innerText;
            // let minimo = element.querySelector('.listing').innerText;
            // let preco = element.querySelector('.listing').innerText;
            let nome = element.childNodes[1].innerText;
            let vencimento = element.childNodes[3].innerText;
            let taxa = element.childNodes[5].innerText;
            let minimo = element.childNodes[7].innerText;
            if (i < 10) {
                let preco = element.childNodes[9].innerText;
                data.push({
                    nome,
                    vencimento,
                    taxa,
                    minimo,
                    preco
                });
            } else {
                data.push({
                    nome,
                    vencimento,
                    taxa,
                    minimo
                });

            }
            i++;

        }
        
        //data.push({"atualizado": updatedTo});
        return {
          updatedTo : updatedTo, 
          data : data,
        }
//         } else {
//           return go;
//         }
    });

    browser.close();
    return result;
};
           scrape().then((value) => {
    console.log(value); // Success!
});
     }
   }
}
  
  
  
  //////? ->>>
  
//           let con = mysql.createConnection({
//           host: "127.0.0.1",
//           user: "laravel-db-admin",
//           password: "O51iofjHWtVrrkEJxRzXCd5R4F2ohsrg",
//           database: "cacoma"
//         });

  
  ///----

          //let go = false;
//         let go = con.connect(function(err) {
//                   if (err) throw err;
//                   con.query("SELECT * FROM users", function (err, result, fields) {
//                     if (err) throw err;
//                     console.log(result);
//                   });
//                 });
  

</script>