const utility = {};

utility.toggleClass = ( element, className ) => {
    if ( element.classList.contains( className ) ) {
        element.classList.remove( className );
    } else {
        element.classList.add( className );
    }
}

utility.sendDebugLog = ( message, ref ) => {
    if ( ! args.debagMode ) return;

    console.log({
        message: `LazyChecks: ${message}`,
        ref,
    });
};

utility.generateRandom = (min = 0, max = 100) => {
    // find diff
    let difference = max - min;

    // generate random number
    let rand = Math.random();

    // multiply with difference
    rand = Math.floor( rand * difference );

    // add with min value
    rand = rand + min;

    return rand;
};

utility.insertAfter = ( targetElement, subject ) => {
    targetElement.parentNode.insertBefore( subject, targetElement.nextSibling );
}

utility.responseStatus = function() {
    this.success  = false;
    this.message  = '';
    this.data     = null;
    this.errors   = null;
    this.warnings = null;
    this.info     = null;

    // Template
    // this.errors  = {
    //   key: 'Error Message',
    // };
    // this.warnings  = {
    //   key: 'Warning Message',
    // };
    // this.info  = {
    //   key: 'Info Message',
    // };
}

utility.jsonToQueryString = json => {
    let string = "?";

    if (!json || Array.isArray(json)) {
        return string;
    }

    if (typeof json !== "object") {
        return string;
    }

    for (const key of Object.keys(json)) {
        string += key + "=" + json[key] + "&";
    }

    return string;
}

export default utility;