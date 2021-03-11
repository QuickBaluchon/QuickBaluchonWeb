function getPackage () {
    let values = getInputsValue(['pkgInput'], true) ;
    let pkg = parseInt(values['pkgInput'])
    if (Number.isInteger(pkg))
        window.location.href += `package/${pkg}` ;
    else
        console.log('NaN')
}