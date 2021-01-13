const loadCharacters = async () => {
    try {
        let hpCharacters = await res.json();
        displayCharacters(hpCharacters);
        console.log(hpCharacters);
    }
    catch (err) {
        console.error(err);
    }


};

const displayCharacters = (characters) => {
    const htmlString = characters
        .map((character) => {
            return `
            <div class="img-area">
            <div class="plant">
            <div class="adImage">
                <img src="images/plant1.jpg" alt="">
            </div>
            <div class="description">
                <h2>${plantName}</h2>
                <br>
                <h3> Afstand: <span>0km</span></h3>
                <h3> Datum: <span>${adDate}</span></h3>
            </div>
            </div>
            </div>
            `;
        })
};


loadCharacters();