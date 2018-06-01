/*jshint esversion: 6 */

racaz = window.racaz || {};

racaz = function() {

  //   var yourVar1;
  //   var yourVar2;
  //formatadores
  const pathArray = window.location.pathname.split('/');
  const slug = pathArray[1];
  const slug2 = pathArray[2];
  const fullSlug = window.location.pathname.slice(1);
  const locale = 'pt-BR';
  //para valores monetarios
  const options = {
    style: 'currency',
    currency: 'brl',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  };
  const currFormatter = new Intl.NumberFormat(locale, options);

  const percentageOptions = {
    style: 'percent',
    minimumFractionDigits: 3,
    maximumFractionDigits: 3
  };
  const percFormatter = new Intl.NumberFormat(locale, percentageOptions);


  const columnDesc = [
    ["id", 'ID'],
    ["name", 'Nome'],
    ["email", 'E-mail'],
    ["role_id", 'Permissão'],
    ["created_at", "Criado em"],
    ["updated_at", "Atual. em"],
    ["timestamp", "Data do reg."],
    ["symbol", "Ticket"],
    ["stock_id", "Ticket"],
    ["type", "Tipo"],
    ["cnpj", "CNPJ"],
    ["open", "Abertura"],
    ["volume", "Volume"],
    ["price", "Preço"],
    ["low", "Baixa"],
    ["high", "Alta"],
    ["close", "Fecham"],
    ["date_invest", "Data inv."],
    ["broker_fee", "Corretagem"],
    ["broker_id", "Corretora"],
    ["broker", "Corretora"],
    ["quote", "Cotação"],
    ["quant", "Quant."],
    ["user_id", "Usuario"],
    ["total", "Total"],
    ["percentage", "%"],
    ["invests", "Investimentos"],
    ["stocks", "Ações"],
    ["stock", "Ação"],
    ["brokers", "Corretoras"],
    ["broker_name", "Corretora"],
    ["users", "Usuário"],
    ["monthlyquotes", "Cotações mensais"],
    ["dailyquotes", "Cotações diarias"],
    ["fail", "Falha"],
    ["success", "Sucesso"],
    ["upToDate", "Atualiz. anteriormente"],
    ["stock_name", "Ação"],
    ["profile", "Perfil"],
  ];

  //variaveis para utilizar no vue datepicker, com a finalidade de limitar a quantidade de datas que podem ser utilizadas

  const dateInvestLimit = {
    disabledDates: {
      //to: new Date(2016, 0, 5), // Disable all dates up to specific date
      from: new Date(), // Disable all dates after specific date
      //from: new Date(new Date().setDate(new Date().getDate()-1)), // Disable all dates after specific date
      days: [6, 0], // Disable Saturday's and Sunday's
      //daysOfMonth: [29, 30, 31], // Disable 29th, 30th and 31st of each month
      //     dates: [ // Disable an array of dates
      //       new Date(2016, 9, 16),
      //       new Date(2016, 9, 17),
      //       new Date(2016, 9, 18)
      //     ],
      //     ranges: [{ // Disable dates in given ranges (exclusive).
      //       from: new Date(2016, 11, 25),
      //       to: new Date(2016, 11, 30)
      //     }, {
      //       from: new Date(2017, 1, 12),
      //       to: new Date(2017, 2, 25)
      //     }],
      // a custom function that returns true if the date is disabled
      // this can be used for wiring you own logic to disable a date if none
      // of the above conditions serve your purpose
      // this function should accept a date and return true if is disabled
      //     customPredictor: function(date) {
      //       // disables the date if it is a multiple of 5
      //       if(date.getDate() % 5 === 0){
      //         return true
      //       }
      //     }
    }
  };


  capitalizeFirstLetter = function(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  };

  columnName = function(column) {
    let iterable = new Map(columnDesc);
    for (let [key, value] of iterable) {
      if (key === column) {
        return value;
      }
    }
    return column;
  };
  fieldsFiller = function(data) {
    let fields = [];
    //depois ele vai fazer o tratamento dos dados apresentados, trazendo para formatos de apresentacao
    if (Array.isArray(data)) {
      for (let value of data) {
        //value = value.replace(/[.\W\d]/g,'');
        //aqui ele formata as datas
        if (value === "timestamp" || value === "date_invest" || value === "data") {
          fields.push({
            key: value,
            label: racaz.columnName(value),
            sortable: true,
            formatter: (value) => {
              return moment(String(value)).format('DD/MM/YYYY hh:mm');
            }
          });
          //aqui formata os precos
        } else if (value === "open" || value === "high" || value === "low" ||
          value === "close" || value === "price" || value === "quote" ||
          value === "broker_fee" || value === "total" ||
          value === "1. open" || value === "2. high" || value === "3. low" || value === "4. close"
        ) {
          fields.push({
            key: value,
            label: racaz.columnName(value.replace(/[.\W\d]/g, '')),
            sortable: true,
            formatter: (value) => {
              return currFormatter.format(parseFloat(value));
              //return value;
            }
          });
          //aqui traz volume para valor inteiro, sem fracao
        } else if (value === "volume" || value === "quant" || value === "5. volume") {
          fields.push({
            key: value,
            label: racaz.columnName(value.replace(/[.\W\d]/g, '')),
            sortable: true,
            formatter: (value) => {
              return parseFloat(value).toFixed(0);
            }
          });
          //acerta a forma de apresentar porcentagem
        } else if (value === "percentage") {
          fields.push({
            key: value,
            label: racaz.columnName(value),
            sortable: true,
            formatter: (value) => {
              return percFormatter.format(value);
            }
          });
          //ajusta o nome do investimento
        } else if (value === "type") {
          fields.push({
            key: value,
            label: racaz.columnName(value),
            sortable: true,
            formatter: (value) => {
              return racaz.columnName(value);
            }
          });
          //o item _cellVariants nao é renderizado
        } else if (value === "_cellVariants" || value === "created_at" || value === "updated_at" || value === "redirect" || value === "user_id") {
          // faz nada
        } else {
          fields.push({
            key: value,
            label: racaz.columnName(value),
            sortable: true,
          });
        }
      }
      return fields;
    } else {
      console.log('fields filler' + data);
    }
  };
  formtt = function(data) {
    //depois ele vai fazer o tratamento dos dados apresentados, trazendo para formatos de apresentacao
    if (Array.isArray(data)) {
      //aqui ele formata as datas
      if (data[0] === "timestamp" || data[0] === "date_invest") {
        data[1] = moment(String(data[1])).format('DD/MM/YYYY hh:mm');
        //data[1] = moment(data[1]).format('DD/MM/YYYY');
        //aqui formata os precos
      } else if (data[0] === "open" || data[0] === "high" || data[0] === "low" || data[0] === "close" || data[0] === "price" || data[0] === "quote" || data[0] === "broker_fee" || data[0] === "total") {
        data[1] = currFormatter.format(data[1]);
        //aqui traz volume para valor inteiro, sem fracao
      } else if (data[0] === "volume" || data[0] === "quant") {
        data[1] = parseFloat(data[1]).toFixed(0);
        //o item _cellVariants nao é renderizado
      } else if (data[0] === "percentage") {
        data[1] = percFormatter.format(data[1]);
        //o item _cellVariants nao é renderizado
      } else if (data[0] === "created_at" || data[0] === "updated_at") {
        data[1] = moment(data[1]).format('DD/MM/YYYY HH:mm:ss');
      } else {
        console.log('Nao formatado pelo formtt, mas sucesso' + data);
      }
      return data[1];
    } else {
      console.log('Nao formatado pelo formtt, nao eh array ' + data);
    }
  };
  unformtt = function(data) {
      //depois ele vai fazer o tratamento dos dados apresentados, trazendo para formatos de apresentacao
      if (Array.isArray(data)) {
        //aqui ele formata as datas
        if (data[0] === "timestamp" || data[0] === "date_invest") {
          //data[1] = moment(String(data[1])).format('DD/MM/YYYY hh:mm');
          data[1] = moment(data[1]).format("YYYY-DD-MM");
          //aqui formata os precos
        } else if (data[0] === "open" || data[0] === "high" || data[0] === "low" || data[0] === "close" || data[0] === "price" || data[0] === "quote" || data[0] === "broker_fee" || data[0] === "total") {
          data[1] = currFormatterNeu(parseFloat(data[1]));
          //aqui traz volume para valor inteiro, sem fracao
        } else if (data[0] === "volume" || data[0] === "quant") {
          data[1] = parseFloat(data[1]).toFixed(0);
          //o item _cellVariants nao é renderizado
        } else if (data[0] === "percentage") {
          data[1] = percFormatter.format(data[1]);
          //o item _cellVariants nao é renderizado
        } else if (data[0] === "created_at" || data[0] === "updated_at") {
          //data[1] = moment(String(data[1])).format('DD/MM/YYYY hh:mm');
          data[1] = moment(data[1]).format("YYYY-DD-MM HH:mm:ss");
        } else {
          console.log('Nao formatado pelo unformtt, mas sucesso' + data);
        }
        return data[1];
      } else {
        console.log('Nao formatado pelo unformtt, nao eh array ' + data);
      }
    };

    //traz para
    //   currFormatterNeu = function (n, currency) {
    //     return currency + " " + n.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");
    //     }
    //currFormatterNeu = (n) => n.toFixed(2).replace(/(\d)(?=(\d{3})+\.)/g, "$1,");

  return {
    "capitalizeFirstLetter": capitalizeFirstLetter,
    "slug": slug,
    "fullSlug": fullSlug,
    "pathArray": pathArray,
    "columnName": columnName,
    "fieldsFiller": fieldsFiller,
    "formtt": formtt,
    "unformtt": unformtt,
    "dateInvestLimit": dateInvestLimit,
    "currFormatter": currFormatter,
  };

}();
