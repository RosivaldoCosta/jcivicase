/**
 * List compiled by mystix on the extjs.com forums.
 * Thank you Mystix!
 *
 * Dutch Translations
 * by Ido Sebastiaan Bas van Oostveen (12 Oct 2007)
 * updated to 2.2 by Condor (8 Aug 2008)
 */

Ext.UpdateManager.defaults.indicatorText = '<div class="loading-indicator">Bezig met laden...</div>';

if(Ext.DataView){
  Ext.DataView.prototype.emptyText = '';
}

if(Ext.grid.GridPanel){
  Ext.grid.GridPanel.prototype.ddText = '{0} geselecteerde rij(en)';
}

if(Ext.LoadMask){
  Ext.LoadMask.prototype.msg = 'Bezig met laden...';
}

Ext.Date.monthNames = [
  'januari',
  'februari',
  'maart',
  'april',
  'mei',
  'juni',
  'juli',
  'augustus',
  'september',
  'oktober',
  'november',
  'december'
];

Ext.Date.getShortMonthName = function(month) {
  if (month == 2) {
    return 'mrt';
  }
  return Ext.Date.monthNames[month].substring(0, 3);
};

Ext.Date.monthNumbers = {
  jan: 0,
  feb: 1,
  mrt: 2,
  apr: 3,
  mei: 4,
  jun: 5,
  jul: 6,
  aug: 7,
  sep: 8,
  okt: 9,
  nov: 10,
  dec: 11
};

Ext.Date.getMonthNumber = function(name) {
  var sname = name.substring(0, 3).toLowerCase();
  if (sname == 'maa') {
    return 2;
  }
  return Ext.Date.monthNumbers[sname];
};

Ext.Date.dayNames = [
  'zondag',
  'maandag',
  'dinsdag',
  'woensdag',
  'donderdag',
  'vrijdag',
  'zaterdag'
];

Ext.Date.getShortDayName = function(day) {
  return Ext.Date.dayNames[day].substring(0, 3);
};

Ext.Date.parseCodes.S.s = "(?:ste|e)";

if(Ext.MessageBox){
  Ext.MessageBox.buttonText = {
    ok: 'OK',
    cancel: 'Annuleren',
    yes: 'Ja',
    no: 'Nee'
  };
}

if(Ext.util.Format){
    Ext.apply(Ext.util.Format, {
        thousandSeparator: '.',
        decimalSeparator: ',',
        currencySign: '\u20ac',  // Dutch Euro
        dateFormat: 'j-m-Y'
    });
}

if(Ext.picker.Date){
  Ext.apply(Ext.DatePicker.prototype, {
    todayText: 'Vandaag',
    minText: 'Deze datum is eerder dan de minimale datum',
    maxText: 'Deze datum is later dan de maximale datum',
    disabledDaysText: '',
    disabledDatesText: '',
    monthNames: Ext.Date.monthNames,
    dayNames: Ext.Date.dayNames,
    nextText: 'Volgende maand (Ctrl+rechts)',
    prevText: 'Vorige maand (Ctrl+links)',
    monthYearText: 'Kies een maand (Ctrl+omhoog/omlaag volgend/vorig jaar)',
    todayTip: '{0} (spatie)',
    format: 'j-m-y',
    okText: '&#160;OK&#160;',
    cancelText: 'Annuleren',
    startDay: 1
  });
}

if(Ext.toolbar.PagingToolbar){
  Ext.apply(Ext.PagingToolbar.prototype, {
    beforePageText: 'Pagina',
    afterPageText: 'van {0}',
    firstText: 'Eerste pagina',
    prevText: 'Vorige pagina',
    nextText: 'Volgende pagina',
    lastText: 'Laatste pagina',
    refreshText: 'Ververs',
    displayMsg: 'Getoond {0} - {1} van {2}',
    emptyMsg: 'Geen gegevens om weer te geven'
  });
}

if(Ext.form.BaseField){
  Ext.form.BaseField.prototype.invalidText = 'De waarde van dit veld is ongeldig';
}

if(Ext.form.Text){
  Ext.apply(Ext.form.Text.prototype, {
    minLengthText: 'De minimale lengte van dit veld is {0}',
    maxLengthText: 'De maximale lengte van dit veld is {0}',
    blankText: 'Dit veld is verplicht',
    regexText: '',
    emptyText: null
  });
}

if(Ext.form.Number){
  Ext.apply(Ext.form.Number.prototype, {
    decimalSeparator : ",",
    decimalPrecision : 2,
    minText: 'De minimale waarde van dit veld is {0}',
    maxText: 'De maximale waarde van dit veld is {0}',
    nanText: '{0} is geen geldig getal'
  });
}

if(Ext.form.Date){
  Ext.apply(Ext.form.Date.prototype, {
    disabledDaysText: 'Uitgeschakeld',
    disabledDatesText: 'Uitgeschakeld',
    minText: 'De datum in dit veld moet na {0} liggen',
    maxText: 'De datum in dit veld moet voor {0} liggen',
    invalidText: '{0} is geen geldige datum - formaat voor datum is {1}',
    format: 'j-m-y',
    altFormats: 'd/m/Y|d-m-y|d-m-Y|d/m|d-m|dm|dmy|dmY|d|Y-m-d'
  });
}

if(Ext.form.ComboBox){
  Ext.apply(Ext.form.ComboBox.prototype, {
    loadingText: 'Bezig met laden...',
    valueNotFoundText: undefined
  });
}

if(Ext.form.VTypes){
  Ext.apply(Ext.form.VTypes, {
    emailText: 'Dit veld moet een e-mail adres bevatten in het formaat "gebruiker@domein.nl"',
    urlText: 'Dit veld moet een URL bevatten in het formaat "http:/'+'/www.domein.nl"',
    alphaText: 'Dit veld mag alleen letters en _ bevatten',
    alphanumText: 'Dit veld mag alleen letters, cijfers en _ bevatten'
  });
}

if(Ext.form.HtmlEditor){
  Ext.apply(Ext.form.HtmlEditor.prototype, {
    createLinkText: 'Vul hier de URL voor de hyperlink in:',
    buttonTips: {
      bold: {
        title: 'Vet (Ctrl+B)',
        text: 'Maak de geselecteerde tekst vet.',
        cls: Ext.baseCSSPrefix + 'html-editor-tip'
      },
      italic: {
        title: 'Cursief (Ctrl+I)',
        text: 'Maak de geselecteerde tekst cursief.',
        cls: Ext.baseCSSPrefix + 'html-editor-tip'
      },
      underline: {
        title: 'Onderstrepen (Ctrl+U)',
        text: 'Onderstreep de geselecteerde tekst.',
        cls: Ext.baseCSSPrefix + 'html-editor-tip'
      },
      increasefontsize: {
        title: 'Tekst vergroten',
        text: 'Vergroot het lettertype.',
        cls: Ext.baseCSSPrefix + 'html-editor-tip'
      },
      decreasefontsize: {
        title: 'Tekst verkleinen',
        text: 'Verklein het lettertype.',
        cls: Ext.baseCSSPrefix + 'html-editor-tip'
      },
      backcolor: {
        title: 'Tekst achtergrondkleur',
        text: 'Verander de achtergrondkleur van de geselecteerde tekst.',
        cls: Ext.baseCSSPrefix + 'html-editor-tip'
      },
      forecolor: {
        title: 'Tekst kleur',
        text: 'Verander de kleur van de geselecteerde tekst.',
        cls: Ext.baseCSSPrefix + 'html-editor-tip'
      },
      justifyleft: {
        title: 'Tekst links uitlijnen',
        text: 'Lijn de tekst links uit.',
        cls: Ext.baseCSSPrefix + 'html-editor-tip'
      },
      justifycenter: {
        title: 'Tekst centreren',
        text: 'Centreer de tekst.',
        cls: Ext.baseCSSPrefix + 'html-editor-tip'
      },
      justifyright: {
        title: 'Tekst rechts uitlijnen',
        text: 'Lijn de tekst rechts uit.',
        cls: Ext.baseCSSPrefix + 'html-editor-tip'
      },
      insertunorderedlist: {
        title: 'Opsommingstekens',
        text: 'Begin een ongenummerde lijst.',
        cls: Ext.baseCSSPrefix + 'html-editor-tip'
      },
      insertorderedlist: {
        title: 'Genummerde lijst',
        text: 'Begin een genummerde lijst.',
        cls: Ext.baseCSSPrefix + 'html-editor-tip'
      },
      createlink: {
        title: 'Hyperlink',
        text: 'Maak van de geselecteerde tekst een hyperlink.',
        cls: Ext.baseCSSPrefix + 'html-editor-tip'
      },
      sourceedit: {
        title: 'Bron aanpassen',
        text: 'Schakel modus over naar bron aanpassen.',
        cls: Ext.baseCSSPrefix + 'html-editor-tip'
      }
    }
  });
}

if(Ext.grid.GridView){
  Ext.apply(Ext.grid.GridView.prototype, {
    sortAscText: 'Sorteer oplopend',
    sortDescText: 'Sorteer aflopend',
    columnsText: 'Kolommen'
  });
}

if(Ext.grid.GroupingView){
  Ext.apply(Ext.grid.GroupingView.prototype, {
  emptyGroupText: '(Geen)',
  groupByText: 'Dit veld groeperen',
  showGroupsText: 'Toon in groepen'
  });
}

if(Ext.grid.PropertyColumnModel){
  Ext.apply(Ext.grid.PropertyColumnModel.prototype, {
    nameText: 'Naam',
    valueText: 'Waarde',
    dateFormat: 'j-m-Y'
  });
}

if(Ext.layout.BorderLayout && Ext.layout.BorderLayout.SplitRegion){
  Ext.apply(Ext.layout.BorderLayout.SplitRegion.prototype, {
    splitTip: 'Sleep om grootte aan te passen.',
    collapsibleSplitTip: 'Sleep om grootte aan te passen. Dubbel klikken om te verbergen.'
  });
}

if(Ext.form.Time){
  Ext.apply(Ext.form.Time.prototype, {
    minText: 'De tijd in dit veld moet op of na {0} liggen',
    maxText: 'De tijd in dit veld moet op of voor {0} liggen',
    invalidText: '{0} is geen geldig tijdstip',
    format: 'G:i',
    altFormats: 'g:ia|g:iA|g:i a|g:i A|h:i|g:i|H:i|ga|ha|gA|h a|g a|g A|gi|hi|gia|hia|g|H'
  });
}

if(Ext.form.CheckboxGroup){
  Ext.apply(Ext.form.CheckboxGroup.prototype, {
    blankText : 'Selecteer minimaal een element in deze groep'
  });
}

if(Ext.form.RadioGroup){
  Ext.apply(Ext.form.RadioGroup.prototype, {
    blankText : 'Selecteer een element in deze groep'
  });
}
