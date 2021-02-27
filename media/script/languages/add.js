function addLanguage () {
    let language = document.getElementById('language').value.trim() ;
    let shortcut = document.getElementById('shortcut').value.trim().toUpperCase() ;
    let flag = document.getElementById('flag').value.trim() ;

    if (checkValues(language, shortcut, flag) == true) {

        if (flag.length == 17) {
            let tmp = flag.substring(9) ;
            flag = flag.substring(0, 8) + tmp ;
        }

        let json = JSON.stringify( {
            language : language,
            shortcut : shortcut,
            flag : flag
        } );
        ajax('/api/languages/', json, 'PUT', success, error) ;
    } else {
        console.log ("Les valeurs ne sont pas remplies")
    }
}

function deleteLanguage (sh) {
    let json = JSON.stringify({ shortcut: sh.toUpperCase() }) ;
    ajax('/api/languages/', json, 'DELETE', success, error) ;
}

function checkValues (language, shortcut, flag) {
    if (language.length < 4 ||shortcut.length != 2 ||flag.length < 16 ||flag.length > 17)
        return false ;

    return true ;
}

function success (text) {
    window.location.reload() ;
}

function error (err) {
    console.log(err) ;
}
