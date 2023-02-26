class Store {
    constructor(url) {
        this.url = url
        this.expirationTime = 1000 * 10
    }

    fetch() {
        fetch(this.url, {
            method: "post",
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
        })
            .then( (response) => response.json()).then(data => {
                this.prepare(data)
            }).then(() => app.createLayout()).then(() => app.addListeners()).then(() => app.getExchangeRate())
    }

    prepare(data) {
        this.data = {}
        for (let key of data) {
            this.data[key['charcode']] = {'nominal': key['nominal'], 'value': parseFloat(key['value'])}
        }
        this.countryList = Object.keys(this.data)
    }
}
class App {
    order = 'reverse'; // reverse | direct
    exchangeRate = 0;
    constructor() {
        window.onload = this.init()
    }
    init() {
        this.store = new Store("/controllers/currency.php/")
        this.store.fetch()
    }

    createLayout() {
        this.amount = document.querySelector("form input");
        this.exchangeRateText = document.querySelector("form .exchange-rate");
        this.exchangeRateText.innerText = "Getting exchange rate...";
        this.dropList = document.querySelectorAll("form select"),
            this.getButton = document.querySelector("form button");
        this.exchangeIcon = document.querySelector("form .icon");

        this.fromDropList = document.querySelector(".from select"),
            this.toDropList = document.querySelector(".to select")
        for(let currency_code of this.store.countryList){
            let optionTag = `<option value="${currency_code}">${currency_code}</option>`;
            if (currency_code === 'USD') {
                optionTag = `<option selected="selected" value="${currency_code}">${currency_code}</option>`;
            }

            this.fromDropList.insertAdjacentHTML("beforeend", optionTag);
        }
        let optionTag = `<option selected="selected" value="RUB">RUB</option>`;
        this.toDropList.insertAdjacentHTML("beforeend", optionTag);
    }

    addListeners() {
        this.fromDropList.addEventListener("change", e => {
            this.loadFlag(e.target);
        });

        this.toDropList.addEventListener("change", e => {

            this.loadFlag(e.target);
        });

        this.getButton.addEventListener("click", e => {
            e.preventDefault();
            this.getExchangeRate();
        });

        this.exchangeIcon.addEventListener("click", () => {
            this.order === 'direct' ? this.order = 'reverse' : this.order = 'direct';
            const tempCode = this.fromDropList.innerHTML;
            const tempSource = this.fromDropList.value
            this.fromDropList.innerHTML = this.toDropList.innerHTML;
            this.fromDropList.value = this.toDropList.value
            this.toDropList.innerHTML = tempCode;
            this.toDropList.value = tempSource;
            this.loadFlag(this.fromDropList);
            this.loadFlag(this.toDropList);
            this.getExchangeRate();
        })
    }

    loadFlag(element){
        const code = element.value
        this.imgTag = element.parentElement.querySelector("img");
        this.imgTag.src = `https://flagcdn.com/48x36/${code.slice(0, 2).toLowerCase()}.png`;
    }

    getExchangeRate(){
        this.amountVal = this.amount.value;
        if(this.amountVal == "" || this.amountVal == "0"){
            this.amount.value = "1";
            this.amountVal = 1;
        }
        if (this.order === 'direct') {
            this.exchangeRate = this.amountVal /
                (this.store.data[this.toDropList.value]['nominal'] * this.store.data[this.toDropList.value]['value'])
        } else {
            this.exchangeRate = this.amountVal * this.store.data[this.fromDropList.value]['value'] * this.store.data[this.fromDropList.value]['nominal']
        }
        this.setExchangeRateText(this.exchangeRate)
    }

    setExchangeRateText(text) {
        this.exchangeRateText.innerText = text;
    }
}

const app = new App()