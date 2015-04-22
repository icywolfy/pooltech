var consoleLog = function (e) {
  console.log(e);
};

pooltechStripe = {
  eInit: false,
  eFullName: null,
  eAddress1: null,
  eAddress2: null,
  eCity: null,
  eState: null,
  eZip: null,
  eCard: null,
  eExpMonth: null,
  eExpYear: null,
  eCardZip: null,
  eCardCvc: null,
  ePhone: null,
  eEmail: null,

  init: function () {
    $('#payment-form').submit(jQuery.proxy(pooltechStripe.processForm, pooltechStripe));
  },

  initElements: function () {
    if (this.eInit) {
      return;
    }
    this.eInit = true;
    this.eFullName = $('#payment-full-name');
    this.eAddress1 = $('#payment-address-1');
    this.eAddress2 = $('#payment-address-2');
    this.eCity = $('#payment-city');
    this.eState = $('#payment-state');
    this.eZip = $('#payment-postal-code');
    this.eCard = $('#payment-cc');
    this.eExpMonth = $('#payment-exp-month');
    this.eExpYear = $('#payment-exp-year');
    this.eCardZip = $('#payment-zip');
    this.eCardCvc = $('#payment-cvv');
    this.ePhone = $('#payment-phone');
    this.eEmail = $('#payment-email');
  },

  processForm: function (e) {
    e.preventDefault();
    this.clearErrors();
    if (!this.validateForm()) {
      this.displayErrors();
      consoleLog('Errors. exiting.');
      return;
    }
    consoleLog('send from to stripe.');
    this.submitFormToStripe();
    return false;
  },

  clearErrors: function () {
    var payment = $('#payment-form');
    payment.find('input').removeClass('error');
    payment.find('select').removeClass('error');
  },

  markElementError: function (el) {
    el.addClass('error');
  },

  displayErrors: function () {
    var formData = this.getFormData();
    if (!this.validateFormItems.fullName(formData.fullName)) {
      this.markElementError(this.eFullName);
    }
    if (!this.validateFormItems.address1(formData.address1)) {
      this.markElementError(this.eAddress1);
    }
    if (!this.validateFormItems.city(formData.city)) {
      this.markElementError(this.eCity);
    }
    if (!this.validateFormItems.zip(formData.zip)) {
      this.markElementError(this.eZip);
    }
    if (!this.validateFormItems.card(formData.card)) {
      this.markElementError(this.eCard);
    }
    if (!this.validateFormItems.cardCvv(formData.cardCvv)) {
      this.markElementError(this.eCardCvc);
    }
    if (!this.validateFormItems.email(formData.email)) {
      this.markElementError(this.eEmail);
    }
    if (!this.validateFormItems.phone(formData.phone)) {
      this.markElementError(this.ePhone);
    }
    if (!this.validateFormItems.cardZip(formData.cardZip)) {
      this.markElementError(this.eCardZip);
    }
  },
  validateFormItems: {
    fullName: function (value) {
      return !!value;
    },
    address1: function (value) {
      return !!value;
    },
    city: function (value) {
      return !!value;
    },
    zip: function (value) {
      if (value.match(/^[0-9]{5}(?:-[0-9]{4})?$/)) {
        return true;
      }
      // default.
      return false;
    },
    luhnChk: (function (arr) {
      return function (ccNum) {
        var
          len = ccNum.length,
          bit = 1,
          sum = 0,
          val;

        while (len) {
          val = parseInt(ccNum.charAt(--len), 10);
          sum += (bit ^= 1) ? arr[val] : val;
        }

        return sum && sum % 10 === 0;
      };
    }([0, 2, 4, 6, 8, 1, 3, 5, 7, 9])),
    card: function (value) {
      if (value.match(/[^0-9 .\\-]/)) {
        return false;
      }
      var num = value.replace(/[^0-9]/g, '');
      if (num.length < 12 || num.length > 20) {
        return false;
      }
      return pooltechStripe.validateFormItems.luhnChk(num);
    },
    cardCvv: function (value) {
      return value.match(/^[0-9]{3,4}$/);
    },
    email: function (value) {
      if (!value) {
        return false;
      }
      if (!value.match('@')) {
        return false;
      }
      // default.
      return true;
    },
    phone: function (value) {
      var num = value.replace(/[^+0-9]/g, '');
      if (num.match(/^\+[0-9]{7,15}$/)) {
        return true;
      }
      if (num.match(/^[2-9][0-8][0-9][2-9]\d{6}$/)) {
        return true;
      }
      // default.
      return false;
    },
    cardZip: function (value) {
      return value.match(/^[a-zA-Z0-9]{2,10}$/);
    }
  },
  validateForm: function () {
    var formData = this.getFormData();
    if (!this.validateFormItems.fullName(formData.fullName)) {
      console.log('fullName');
      return false;
    }
    if (!this.validateFormItems.address1(formData.address1)) {
      console.log('address1')
      return false;
    }
    if (!this.validateFormItems.city(formData.city)) {
      console.log('city')
      return false;
    }
    if (!this.validateFormItems.zip(formData.zip)) {
      console.log('zip')
      return false;
    }
    if (!this.validateFormItems.card(formData.card)) {
      console.log('card')
      return false;
    }
    if (!this.validateFormItems.cardCvv(formData.cardCvv)) {
      console.log('cvv')
      return false;
    }
    if (!this.validateFormItems.email(formData.email)) {
      console.log('email')
      return false;
    }
    if (!this.validateFormItems.phone(formData.phone)) {
      console.log('phone')
      return false;
    }
    if (!this.validateFormItems.cardZip(formData.cardZip)) {
      console.log('zip')
      return false;
    }
    return true;
  },

  getFormData: function () {
    var formData = {};
    this.initElements();
    formData.fullName = this.eFullName.val().trim();
    formData.address1 = this.eAddress1.val().trim();
    formData.address2 = this.eAddress2.val().trim();
    formData.city = this.eCity.val().trim();
    formData.state = this.eState.val().trim();
    formData.zip = this.eZip.val().trim();
    formData.card = this.eCard.val().trim();
    formData.expMonth = this.eExpMonth.val().trim();
    formData.expYear = this.eExpYear.val().trim();
    formData.cardZip = this.eCardZip.val().trim();
    formData.cardCvv = this.eCardCvc.val().trim();
    formData.phone = this.ePhone.val().trim();
    formData.email = this.eEmail.val().trim();
    return formData;
  },


  submitFormToStripe: function () {
    var paymentForm, paymentSubmit;
    paymentForm = $('#payment-form');
    paymentSubmit = $('#order-submit-button');
    paymentSubmit.prop('disabled', true);
    Stripe.card.createToken(paymentForm, pooltechStripe.tokenHandler);
    return false;
  },

  tokenHandler: function (status, response) {
    var ps = pooltechStripe;
    if (response.error) {
      $('#order-submit-button').attr('disabled', false);
      ps.clearErrors();
      ps.markElementError(ps.eCard);
      ps.markElementError(ps.eCardCvc);
      ps.markElementError(ps.eExpYear);
      ps.markElementError(ps.eExpMonth);
      ps.markElementError(ps.eCardZip);
      return;
    }
    var token = response.id;
    var paymentForm = $('#payment-form');
    paymentForm.append($('<input type="hidden" name="token" />').val(token));
    paymentForm.append($('<input type="hidden" name="tokenData" />').val($.param(response)));

  }
};

pooltech = {
  priceButtonDollarAmount: null,
  overviewPriceDisplay: null,
  overviewSubscriptionType: null,
  summaryPriceDisplay: null,
  summarySensorDisplay: null,
  pricePanel: null,

  init: function () {
    var order = $('#order-choice');
    pooltech.pricePanel = order;
    pooltech.priceButtonDollarAmount = $('#place-order-dollar-amount');
    pooltech.overviewPriceDisplay = $('#overview-total-amount');
    pooltech.overviewSubscriptionType = $('#overview-subscription-type');
    pooltech.summaryPriceDisplay = $('#subscription-cost-amount');
    pooltech.summarySensorDisplay = $('#sensor-cost-amount');
    $('#pool-length-year').click();
    order.on('shown.bs.tab', '.nav-tabs a', pooltech.propagateSelectedTimeframe);
    order.on('shown.bs.tab', '.nav-tabs a', pooltech.updatePriceButton);
    order.on('click', 'input', pooltech.updatePriceButton);
  },


  updatePriceButton: function () {
    var checkedElement = pooltech.pricePanel.find('input:checked');
    var amount = checkedElement.data('first-month');
    var sensor = checkedElement.data('sensor');
    var description = checkedElement.val() === 'year' ? '1-year subscription' : 'Month-to-month subscription';
    pooltech.priceButtonDollarAmount.text(amount);
    pooltech.overviewPriceDisplay.text(amount);
    pooltech.summaryPriceDisplay.text(amount);
    pooltech.summarySensorDisplay.text(sensor);
    pooltech.overviewSubscriptionType.text(description);
    var sensorElement = $('#sensor-dollar-sign');
    if ((""+sensor).match(/^[0-9]/)) {
      sensorElement.removeClass('hidden');
    } else {
      sensorElement.addClass('hidden');
    }
  },

  propagateSelectedTimeframe: function (e) {
    var prev, curr, prevPanel, currPanel, prevSelected;
    prev = $(e.relatedTarget);
    curr = $(e.target);
    prevPanel = $("" + prev.attr('href'));
    currPanel = $("" + curr.attr('href'));
    prevSelected = "" + prevPanel.find('input:checked').val();
    currPanel.find('input').each(function (index, element) {
      if ($(element).val() === prevSelected) {
        $(element).click();
      }
    });
  }
};

console.log("loaded.");
$(function () {
  pooltech.init();
  pooltechStripe.init();
});
console.log("file-load complete.");
