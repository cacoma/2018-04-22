/*jshint esversion: 6 */

/*classe errors para tratar erro em forms
 */
export class Errors {

  //constructor que inicializa a classe vazia
  constructor() {
    this.errors = {};
  }

  //funcao get para pegar os erros que retornam do servidor
  get(field) {
    if (this.errors[field]) {

      return this.errors[field][0];

    }
  }
  //faz update dos erros que chegam do servidor
  record(errors) {
    console.log(errors)
    this.errors = errors;
  }

  has(field) {
    return this.errors.hasOwnProperty(field);
  }

  clear(field) {
    if (field) {
      delete this.errors[field];
      return;
    }
    this.errors = {};
  }

  any() {
    return Object.keys(this.errors).length > 0;
  }
}
//export default Errors;

export class Modal {
  constructor() {
    this.modal = {};
  }
  record(modal) {
    this.modal = modal;
  }
  get(field) {
    if (this.modal[field]) {
      return this.modal[field];
    }
  }
  any() {
    return Object.keys(this.modal).length > 0;
  }
  clear(field) {
    if (field) {
      delete this.modal[field];
      return;
    }
    this.modal = {};
  }
}

export class Form {
  constructor(data) {
    //this.originalData = data;

    for (let field in data) {
      this[field] = data[field];
    }

    this.errors = new Errors();
    this.modal = new Modal();
  }
  data() {
    let data = Object.assign({}, this);
    return data;
  }
  reset() {

    this.errors.clear();
    for (let field in this) {
      if (field !== 'errors' && field !== 'modal') {
        this[field] = '';
      }
    }
  }
  post(url) {
    return this.submit('post', url);
  }
  patch(url) {
    return this.submit('patch', url);
  }
  submit(requestType, url) {
    this.modal.clear();
    return new Promise((resolve, reject) => {
      //document.getElementById("blur").classList.add("blur");
      //$(".sk-cube-grid").fadeIn("slow");
      loadingon();
      axios[requestType](url, this.data())
        .then(response => {
          this.onSuccess(response.data);
          resolve(response.data);
        })
        .catch(error => {
          console.log(error);
          this.onFail(error.response.data);
          reject(error.response.data);
        });
    });
  }
  onSuccess(data) {
    //document.getElementById("blur").classList.remove("blur");
    //$(".sk-cube-grid").fadeOut("slow");
    loadingoff();
    flash(data.message);
  }

  onFail(errors) {
    //document.getElementById("blur").classList.remove("blur");
    //$(".sk-cube-grid").fadeOut("slow");
    loadingoff();
    console.log(errors);
    this.errors.record(errors.errors);
  }
}
