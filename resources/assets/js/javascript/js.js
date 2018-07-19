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
  const decimalOptions = {
    style: 'decimal',
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
  };
  const currFormatter = new Intl.NumberFormat(locale, options);

  const numberForm = new Intl.NumberFormat(locale, decimalOptions);

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
    ["treasuries", "Titulos"],
    ["treasury", "Titulo"],
    ["due_date", "Vencimento"],
    ["coupon", "Juros semestrais"],
    ["coupon_date", "Prim. pag. de juros"],
    ["coupon_date2", "Seg. pag. de juros"],
    ["code", "Código"],
    ["0", "Não"],
    ["1", "Sim"],
    ["rate", "Taxa"],
    ["avgprice", "Preço médio"],
    ["sumquant", "Quantidade"],
    ["index", "Índice"],
    ["ir", "IR"],
    ["name", "Nome"],
    ["securities", "Renda fixa"],
    ["security", "Renda fixa"],
    ["liquidity", "Liquidez"],
    ["fgc", "FGC"],
    ["issuer_name", "Emissor"],
    ["issuer", "Emissor"],
    ["issuers", "Emissores"],
    ["inv", "Invest."],
    ["bc_code", "Codigo BC"],
    ["unit", "Unidade"],
    ["type_invest", "Tipo"],
    ["designation", "Investimento"],
    ["total_invested", "Tot. inv."],
    ["total_updated", "Tot. atual."],
    ["dif_percentage", "%"],
    ["dif_reais", "Dif. (R$)"],
    ["date_updated", "Data at."],
    ["funds", "Fundos"],
    ["fund", "Fundo"],
    ["reg_date", "Data de registro"],
    ["const_date", "Data de constituição"],
    ["canc_date", "Data de cancelamento"],
    ["sit", "Situação"],
    ["classe", "Classe"],
    ["rentabilidade", "Rentabilidade"],
    ["inv_qual", "Investidor qualificado"],
    ["fundo_exc", "Fundo exclusivo"],
    ["fundo_cotas", "Fundo de cotas"],
    ["taxa_perf", "Taxa de performance"],
    ["diretor", "Diretor"],
    ["admin", "Administrador"],
    ["gestor", "Gestor"],
    ["auditor", "Auditor"],
  ];

  const cvmWords = [
    ["CRï¿½DITO", "CREDITO"],
    ["Aï¿½ï¿½ES", "ACOES"],
    ["ALOCAï¿½ï¿½O", "ALOCACAO"],
    ["CONVERGï¿½NCIA", "CONVERGENCIA"],
    ["EFIGï¿½NIA", "EFIGENIA"],
    ["APLICAï¿½ï¿½O", "APLICACAO"],
    ["Nï¿½VEL", "NIVEL"],
    ["TRï¿½PICO", "TROPICO"],
    ["PORTIFï¿½LIO", "PORTFOLIO"],
    ["MULTIESTRATï¿½GIA", "MULTIESTRATEGIA"],
    ["ALDEBARï¿½", "ALDEBARA"],
    ["DEBï¿½NTURES", "DEBENTURES"],
    ["Dï¿½VIDA", "DIVIDA"],
    ["ï¿½RAMA", "ORAMA"],
    ["GESTï¿½O", "GESTAO"],
    ["ADMINISTRAï¿½ï¿½O", "ADMINISTRACAO"],
    ["Tï¿½TULOS", "TITULOS"],
    ["MOBILIï¿½RIOS", "MOBILIARIOS"],
    ["ï¿½NDICE", "INDICE"],
    ["ï¿½ndice", "Indice"],
    ["PREVIDï¿½NCIA", "PREVIDENCIA"],
    ["PREï¿½O", "PRECO"],
    ["PREï¿½OS", "PRECOS"],
    ["MULTI-ï¿½NDICES", "MULTI-INDICES"],
    ["CAMBURIï¿½", "CAMBORIU"],
    ["ITAï¿½", "ITAU"],
    ["PERSONNALITï¿½", "PERSONALITE"],
    ["ARMAZï¿½M", "ARMAZEM"],
    ["ALIANï¿½A", "ALIANCA"],
    ["Pï¿½BLICOS", "PUBLICOS"],
    ["COMPENSAï¿½ï¿½ES", "COMPENSACOES"],
    ["GOIï¿½S", "GOIAS"],
    ["HIDROGRï¿½FICAS", "HIDROGRAFICAS"],
    ["HIDROGRï¿½FICA", "HIDROGRAFICA"],
    ["FAMï¿½LIA", "FAMILIA"],
    ["PARAï¿½BA", "PARAIBA"],
    ["CONSTRUï¿½ï¿½O", "CONSTRUCAO"],
    ["CONSTRUï¿½ï¿½O", "CONSTRUCAO"],
    ["CRï¿½D", "CRED."],
    ["CONCESSï¿½ES", "CONCESSOES"],
    ["BENEFï¿½CIO", "BENEICIO"],
    ["ALOCAï¿½ï¿½O", "ALOCACAO"],
    ["Aï¿½ï¿½es", "Acoes"],
    ["CR�DITO", "CREDITO"],
    ["A��ES", "ACOES"],
    ["ALOCA��O", "ALOCACAO"],
    ["CONVERG�NCIA", "CONVERGENCIA"],
    ["EFIG�NIA", "EFIGENIA"],
    ["APLICA��O", "APLICACAO"],
    ["N�VEL", "NIVEL"],
    ["TR�PICO", "TROPICO"],
    ["PORTIF�LIO", "PORTFOLIO"],
    ["MULTIESTRAT�GIA", "MULTIESTRATEGIA"],
    ["ALDEBAR�", "ALDEBARA"],
    ["DEB�NTURES", "DEBENTURES"],
    ["D�VIDA", "DIVIDA"],
    ["�RAMA", "ORAMA"],
    ["GEST�O", "GESTAO"],
    ["ADMINISTRA��O", "ADMINISTRACAO"],
    ["T�TULOS", "TITULOS"],
    ["MOBILI�RIOS", "MOBILIARIOS"],
    ["�NDICE", "INDICE"],
    ["�ndice", "Indice"],
    ["PREVID�NCIA", "PREVIDENCIA"],
    ["PRE�O", "PRECO"],
    ["PRE�OS", "PRECOS"],
    ["MULTI-�NDICES", "MULTI-INDICES"],
    ["CAMBURI�", "CAMBORIU"],
    ["ITA�", "ITAU"],
    ["PERSONNALIT�", "PERSONALITE"],
    ["ARMAZ�M", "ARMAZEM"],
    ["ALIAN�A", "ALIANCA"],
    ["P�BLICOS", "PUBLICOS"],
    ["COMPENSA��ES", "COMPENSACOES"],
    ["GOI�S", "GOIAS"],
    ["HIDROGR�FICAS", "HIDROGRAFICAS"],
    ["HIDROGR�FICA", "HIDROGRAFICA"],
    ["FAM�LIA", "FAMILIA"],
    ["PARA�BA", "PARAIBA"],
    ["CONSTRU��O", "CONSTRUCAO"],
    ["CONSTRU��O", "CONSTRUCAO"],
    ["CR�D", "CRED."],
    ["CONCESS�ES", "CONCESSOES"],
    ["BENEF�CIO", "BENEICIO"],
    ["ALOCA��O", "ALOCACAO"],
    ["A��es", "Acoes"],
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
        if (value === "timestamp" || value === "date_invest" || value === "data" || value === "due_date" || value === "date_updated") {
          fields.push({
            key: value,
            label: racaz.columnName(value),
            sortable: true,
            formatter: (value) => {
              // return moment(String(value)).format('DD/MM/YYYY hh:mm');
              return moment(String(value)).format('DD/MM/YYYY');
            }
          });
          //aqui formata os precos
        } else if (value === "open" || value === "high" || value === "low" ||
          value === "close" || value === "price" || value === "quote" ||
          value === "broker_fee" || value === "total" ||
          value === "1. open" || value === "2. high" || value === "3. low" ||
          value === "4. close" || value === "avgprice" ||
          value === "total_invested" || value === "total_updated" ||
          value === "dif_reais"
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
              //return parseFloat(value).toFixed(2);
              return numberForm.format(value);
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
        } else if (value === "rate") {
          fields.push({
            key: value,
            label: racaz.columnName(value),
            sortable: true,
            formatter: (value) => {
              return percFormatter.format(value / 100);
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
        } else if (value === "coupon" || value === "fgc") {
          fields.push({
            key: value,
            label: racaz.columnName(value),
            sortable: true,
            formatter: (value) => {
              //return racaz.columnName(value);
              if (value === 0) {
                return 'Não';
              } else {
                return 'Sim';
              }
            }
          });
          //o item _cellVariants nao é renderizado
        } else if (value === "_cellVariants" || value === "created_at" || value === "updated_at" || value === "redirect" ||
          value === "user_id" || value === "issuer_id" || value === "security_id" || value === "canc_date" ||
          value === "const_date" || value === "reg_date" || value === "fundos_cotas" || value === "fundo_exc" ||
          value === "inv_qual" || value === "ir" || value === "taxa_perf" || value === "auditor" || value === "diretor" ||
          value === "fundo_cotas"
          //|| value === "name" || value === "code" || value === "symbol") {
        ) {
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
      if (data[0] === "timestamp" || data[0] === "date_invest" || data[0] === "date_updated") {
        data[1] = moment(String(data[1])).format('DD/MM/YYYY hh:mm');
        //data[1] = moment(data[1]).format('DD/MM/YYYY');
        //aqui formata os precos
      } else if (data[0] === "open" || data[0] === "high" || data[0] === "low" || data[0] === "close" ||
        data[0] === "price" || data[0] === "quote" || data[0] === "broker_fee" || data[0] === "dif_reais" ||
        data[0] === "total" || data[0] === "total_invested" || data[0] === "total_updated") {
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
      } else if (data[0] === "dif_percentage") {
        data[1] = data[1] + "%";
        //       } else if (data[0] === "fundo_cotas") {
        //         data[1] = data[1] === 1 ? "Sim" : "Não";
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

  function round(value, exp) {
    if (typeof exp === 'undefined' || +exp === 0)
      return Math.round(value);

    value = +value;
    exp = +exp;

    if (isNaN(value) || !(typeof exp === 'number' && exp % 1 === 0))
      return NaN;

    // Shift
    value = value.toString().split('e');
    value = Math.round(+(value[0] + 'e' + (value[1] ? (+value[1] + exp) : exp)));

    // Shift back
    value = value.toString().split('e');
    return +(value[0] + 'e' + (value[1] ? (+value[1] - exp) : -exp));
  };

  function removeAcento(s) {
    var i = 'ÀÁÂÃÄÅàáâãäåÒÓÔÕÕÖŐòóôõöőÈÉÊËèéêëðÇçÐÌÍÎÏìíîïÙÚÛÜŰùúûüűÑñŠšŸÿýŽž'.split('');
    var o = 'AAAAAAaaaaaaOOOOOOOooooooEEEEeeeeeCcDIIIIiiiiUUUUUuuuuuNnSsYyyZz'.split('');
    var map = {};
    i.forEach(function(el, idx) {
      map[el] = o[idx]
    });
    return s.replace(/[^A-Za-z0-9]/g, function(ch) {
      return map[ch] || ch;
    })
  }


  function cvmStringReplace(s) {
    return s.
    split(" ").
    map(word => {
      let iterable = new Map(cvmWords);
      for (let [key, value] of iterable) {
        if (key === word) {
          return value;
        }
      }
      return word;
    }).
    join(" ");
  }

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
    "numberForm": numberForm,
    "round": round,
    "removeAcento": removeAcento,
    "cvmStringReplace": cvmStringReplace
  };

}();