class fundsInfo {
  constructor() {
    this.counter = 1;
  }

  get(cnpj) {
    return new Promise((resolve, reject) => {
      loadingon();
      progressBar("Baixando arquivo da CVM...");
      this.getLastWorkingDateUrl(0)
        .then(url => this.downloadCVM(url))
        .then(data => this.papaParse(data, cnpj))
        .then(result => {
          this.onSuccess(result)
          resolve(result)
        })
        .catch(error => {
          console.log(error);
          this.onFail(error);
          reject(error);
        })

      //         .catch(error => {
      //           console.log(error);
      //           this.onFail(error.response);
      //           reject(error.response);
      //         });
    });
  }

  downloadCVM(url) {
    return new Promise((resolve, reject) => {
      axios.get(url, {
          onDownloadProgress: function(progressEvent) {
            let progress = Math.round((progressEvent.loaded * 100) / progressEvent.total)
            //console.log(progress)
            progressBarValue(progress);
          }
        })
        .then(result => {
          resolve(result.data);
        })
        //erro no pegar arquivo na CVM
        .catch((error) => {
          if (error.response) {
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx
            console.log(error.response.data);
            console.log(error.response.status);
            console.log(error.response.headers);
            if (error.response.status === 502) {
              console.log('erro de proxy')
              if (this.counter < 5) {
                this.getLastWorkingDateUrl(this.counter)
              }
            }
          } else if (error.request) {
            // The request was made but no response was received
            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
            // http.ClientRequest in node.js
            console.log(error.request);
          } else {
            // Something happened in setting up the request that triggered an Error
            console.log('Error', error.message);
          }
          console.log(error.config);
          this.onFail(error);
          reject(error.response.status)
        });
      //             .catch(error => {

      //               console.log(error);
      //               this.onFail(error.response);
      //               reject(error.response);
      //             });
    })
  }

  getLastWorkingDateUrl(days) {
    return new Promise((resolve, reject) => {
      let date = moment().subtract(days, 'day').format('YYYY-MM-DD');
      console.log(`date: ${date}`);
      axios.get(`/api/lastworkingdate/${date}`)
        .then(response => {
          let url = `https://cacoma.tk/cvm/dados/FI/CAD/DADOS/inf_cadastral_fi_${moment(response.data[0].date).format('YYYYMMDD')}.csv`
          resolve(url);
        })
        .catch(function(error) {
          if (error.response) {
            // The request was made and the server responded with a status code
            // that falls out of the range of 2xx
            console.log(error.response.data);
            console.log(error.response.status);
            console.log(error.response.headers);
            reject(error.response.data);
          } else if (error.request) {
            // The request was made but no response was received
            // `error.request` is an instance of XMLHttpRequest in the browser and an instance of
            // http.ClientRequest in node.js
            console.log(error.request);
            reject(error.request);
          } else {
            // Something happened in setting up the request that triggered an Error
            console.log('Error', error.message);
            reject(error.message);
          }
          console.log(error.config);
          this.onFail(error);
          reject(error.response.status)
        });
    })
  }

  papaParse(data, cnpj) {
    return new Promise((resolve, reject) => {
      let fund = {}
      Papa.parse(data, {
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
      resolve(fund)
    })
  }

  onSuccess(data) {
    loadingoff();
    progressBarValue(100);
    //flash(data);
  }

  onFail(error) {
    loadingoff();
    progressBarValue(100);
    flash('Erro ao baixar arquivo da CVM.|danger')
    console.log(error);
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