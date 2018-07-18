class fundsInfo {
  //constructor que inicializa a classe vazia
  constructor() {
    this.fundInfo = {};
  }

  get(cnpj) {
    return new Promise((resolve, reject) => {
      loadingon();
      progressBar("Baixando arquivo da CVM...");
      axios.get('/api/lastworkingdate')
        .then(response => {
          let url = `https://cacoma.tk/cvm/dados/FI/CAD/DADOS/inf_cadastral_fi_${moment(response.data[0].date).format('YYYYMMDD')}.csv`
          axios.get(url, {
              onDownloadProgress: function(progressEvent) {
                let progress = Math.round((progressEvent.loaded * 100) / progressEvent.total)
                //console.log(progress)
                progressBarValue(progress);
              }
              // Do whatever you want with the native progress event
            })
            .then(result => {
              let fund = {};
              Papa.parse(result.data, {
                // download: true,
                // worker: true,
                header: true,
                dynamicTyping: true,
                step: row => {
                  if (row.data[0].CNPJ_FUNDO === cnpj) {
                    // console.log("Row:", row.data[0][0])
                    console.log("Row:", row.data)
                    fund = row.data
                  }
                },
                //             complete: () => {
                //               console.log('All done!')
                //             },
                error: err => {
                  this.onFail(err);
                  reject(err);
                }
              })
              this.onSuccess(fund);
              resolve(fund);
            })
            .catch(error => {
              console.log(error);
              this.onFail(error.response.data);
              reject(error.response.data);
            });
        })
        .catch(error => {
          console.log(error);
          this.onFail(error.response.data);
          reject(error.response.data);
        });
    });
  }
  onSuccess(data) {
    loadingoff();
    progressBarValue(100);
    //flash(data);
  }

  onFail(errors) {
    loadingoff();
    progressBarValue(100);
    console.log(errors);
  }
}
module.exports = new fundsInfo()


//       axios.get('/api/lastworkingdate')
//         .then(response => {
//           console.log(response)
//           // let url = `http://dados.cvm.gov.br/dados/FI/CAD/DADOS/inf_cadastral_fi_${moment(response.data[0].date).format('YYYYMMDD')}.csv`
//           console.log(path)
//           Papa.parse(result.data, {
//             // download: true,
//             // worker: true,
//             header: true,
//             dynamicTyping: true,
//             step: row => {
//               //console.log("Row:", row.data)
//               // console.log()
//               if (row.data[0].CNPJ_FUNDO === '14.167.479/0001-60') {
//                 console.log("Row:", row.data[0][0])
//               }
//             },
// //             complete: () => {
// //               console.log('All done!')
// //             },
//             error: err => {
//               console.log(err)
//             }
//           })
// //           })
//         })
//       }