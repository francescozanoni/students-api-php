/**
 * Populate a form with the content of a JSON object.
 *
 * @param {object} form jQuery object wrapping a form
 * @param {object} data plain JSON object
 *
 * @see https://stackoverflow.com/questions/7298364/using-jquery-and-json-to-populate-forms
 */
function populateForm(form, data) {

    $.each(data, function (key, value) {
        var field = $("[name=" + key + "]", form);
        if (field.is("select")) {
            $("option", field).each(function () {
                this.selected = (this.value === value);
            });
        } else {
            switch (field.prop("type")) {
                case "radio" :
                    field.each(function () {
                        $(this).prop("checked", $(this).prop("value") === value);
                    });
                    break;
                case "checkbox":
                    field.prop("checked", [true, 1, "1", "Y", "y", "Yes", "yes", "true", "TRUE"].indexOf(value) !== -1);
                    break;
                default:
                    field.val(value);
            }
        }
    });

}

/**
 * @param {string} tableSelector CSS selector of table DOM element
 * @param {string} url URL to be invoked via AJAX to retrieve data
 *
 * @returns {object} e.g. {
 *                          ajax: {
 *                            url: "../students/1",
 *                            method: "GET",
 *                            cache: true,
 *                            error: function (jqXHR, textStatus, errorThrown) {
 *                                [...]
 *                            }
 *                          },
 *                          paging: false,
 *                          searching: false,
 *                          ordering: false,
 *                          info: false
 *                        }
 */
function getBaseDataTableSettings(tableSelector, url) {
    return {
        ajax: {
            url: url,
            method: "GET",
            cache: true,
            // https://stackoverflow.com/questions/32814120/jquery-datatables-show-no-results-message-on-404-ajax-response
            error: function (jqXHR, textStatus, errorThrown) {
                // In case of no data found, no error must be reported.
                if (jqXHR.responseText === '{"status_code":404,"status":"Not Found","message":"Resource(s) not found"}') {
                    $(tableSelector).DataTable().clear().draw();
                } else {
                    throw errorThrown
                }
            }
        },
        paging: false,
        searching: false,
        ordering: false,
        info: false
    };
}

/**
 * @param {string} field
 *
 * @returns {string}
 *
 * @todo load dynamically from configuration or database
 */
function getFieldLabel(field) {
    var fieldsAndLabels = {
        "audits.0.created_at": "Date/time",
        "content": "Content",
        "credits": "Credits",
        "e_mail": "E-mail",
        "educational_activity": "Educational activity",
        "end_date": "End",
        "first_name": "First name",
        "hour_amount": "Hour amount",
        "id": "ID",
        "is_eligible": "Is eligible",
        "is_interrupted": "Is interrupted",
        "is_optional": "Is optional",
        "last_name": "Last name",
        "location": "Location",
        "nationality": "Nationality",
        "notes": "Notes",
        "other_amount": "Night shift amount",
        "phone": "Phone number",
        "start_date": "Start",
        "sub_location": "Ward",
        "title": "Title",
        // Evaluation item labels: to be loaded dynamically from configuration or database
        "item_1_1": "1.1 - Comunicare con la persona assistita e con le persone di riferimento esercitando ascolto attivo, facilitando l'espressione di ciò che vivono e di ciò che pensano, astenendosi da giudizi di valore.",
        "item_1_2": "1.2 - Fornire alla persona assistita e alle persone di riferimento informazioni precise e comprensibili sulle attività assistenziali programmate.",
        "item_1_3": "1.3 - Riconoscere le reazioni e le emozioni della persona assistita e delle persone di riferimento in rapporto al problema di salute, al trattamento, alle prospettive di vita.",
        "item_1_4": "1.4 - Fornire alla persona assistita risposte adattate alle sue condizioni, alla sua cultura, al suo livello di responsività.",
        "item_2_1": "2.1 - Identificare, attraverso un'anamnesi infermieristica mirata, le informazioni significative sul contesto socio-famigliare, sulla cultura, sulla storia e sulle abitudini di vita della persona assistita.",
        "item_2_2": "2.2 - Reperire dalla documentazione sanitaria le informazioni cliniche significative",
        "item_2_3": "2.3 - Valutare, utilizzando se necessario strumenti specifici, i livelli di autonomia della persona assistita e delle persone di riferimento e le loro necessità di aiuto, in relazione al quadro clinico, alla prognosi e alla dimissione.",
        "item_2_4": "2.4 - Formulare l'enunciato diagnostico in modo pertinente alle valutazioni effettuate.",
        "item_2_5": "2.5 - Stabilire, in relazione alle valutazioni effettuate, le priorità assistenziali della persona assistita.",
        "item_3_1": "3.1 - Programmare gli interventi assistenziali standard per affrontare le necessità di aiuto della persona assistita, tenendo in considerazione le sue condizioni ed eventuali variabili connesse al percorso diagnostico-terapeutico.",
        "item_3_2": "3.2 - Prevedere i possibili miglioramenti del livello di autonomia della persona assistita in base alle sue condizioni cliniche, alla prognosi e alle sue potenzialità.",
        "item_3_3": "3.3 - Identificare e programmare gli interventi assistenziali personalizzati per affrontare i bisogni di assistenza infermieristica, anche in vista della dimissione, tenendo in considerazione le prove di efficacia, le risorse disponibili, i principi etico deontologici.",
        "item_3_4": "3.4 - Condividere, per quanto possibile, con la persona assistita e con le persone di riferimento i risultati attesi e il programma di interventi.",
        "item_4_1": "4.1 - Informare la persona assistita sull'intervento da effettuare e ricercarne il consenso.",
        "item_4_2": "4.2 - Eseguire le tecniche di propria competenza rispettando le regole di sicurezza e gli standard di qualità (da certificare sullo specifico libretto).",
        "item_4_3": "4.3 - Erogare le prestazioni rispettando le procedure, i protocolli, le norme di sicurezza, i principi etici e deontologici.",
        "item_4_4": "4.4 - Erogare, in base alle priorità evidenziate, le attività assistenziali personalizzate, sostenendo per quanto possibile l'autonomia e la cooperazione della persona assistita e delle persone di riferimento.",
        "item_4_5": "4.5 - Identificare le difficoltà che la persona assistita può incontrare durante lo svolgimento delle attività assistenziali e mettere in atto i necessari adattamenti.",
        "item_5_1": "5.1 - Registrare le informazioni nella documentazione sanitaria in modo preciso, sintetico e utilizzando un linguaggio appropriato.",
        "item_5_2": "5.2 - Valutare i risultati delle prestazioni infermieristiche.",
        "item_5_3": "5.3 - Monitorare le reazioni e l'evoluzione delle condizioni della persona assistita evidenziando segnali di scarsa aderenza agli interventi e relative cause.",
        "item_5_4": "5.4 - Valutare il livello di raggiungimento degli obiettivi assistenziali concordati.",
        "item_5_5": "5.5 - Rielaborare, se necessario, il programma di interventi sulla base della valutazione dei risultati raggiunti.",
        "item_6_1": "6.1 - Applicare le prescrizioni diagnostiche e terapeutiche utilizzando le procedure, i protocolli e le tecnologie",
        "item_6_2": "6.2 - Informare la persona assistita, anche in collaborazione con il medico e con altri professionisti, sulle finalità e modalità di preparazione e attuazione delle prescrizioni diagnostiche e terapeutiche, verificandone il livello di comprensione e il consenso.",
        "item_6_3": "6.3 - Programmare le attività connesse alle prescrizioni diagnostiche e terapeutiche tenendo conto delle modalità e dei tempi stabiliti, delle esigenze della persona assistita e delle variabili del contesto organizzativo.",
        "item_7_1": "7.1 - Rilevare e segnalare segni e sintomi indicatori di malessere della persona assistita connessi al suo trattamento.",
        "item_7_2": "7.2 - Rilevare e segnalare effetti attesi e indesiderati, anche critici, dei trattamenti, insorgenza di complicanze e adottare i necessari provvedimenti.",
        "item_8_1": "8.1 - Programmare e realizzare le proprie attività tenendo conto delle esigenze della persona assistita e delle variabili del contesto organizzativo.",
        "item_8_2": "8.2 - Valutare le priorità assistenziali delle persone affidate.",
        "item_8_3": "8.3 - Programmare il proprio piano di lavoro giornaliero in relazione alle priorità assistenziali, alle risorse e ai vincoli del contesto organizzativo.",
        "item_8_4": "8.4 - Verificare lo svolgimento delle attività programmate e apportare, se necessario, correttivi al piano di lavoro.",
        "item_8_5": "8.5 - Identificare situazioni di criticità organizzativa pertinenti alle attività di sua competenza.",
        "item_9_1": "9.1 - Identificare i compiti attribuibili al personale di supporto in base alle condizioni della persona assistita e alla natura e complessità dell'attività assistenziale.",
        "item_9_2": "9.2 - Attribuire al personale di supporto le attività programmate secondo i protocolli e il livello di complessità assistenziale della persona assistita.",
        "item_10_1": "10.1 - Fornire informazioni e riflessioni utili per comprendere e analizzare con l'èquipe problematiche assistenziali, etico-deontologiche, organizzative.",
        "item_10_2": "10.2 - Analizzare con l'èquipe situazioni connesse alla qualità dell'assistenza e ricercare soluzioni appropriate.",
        "item_11_1": "11.1 - Autovalutare le proprie conoscenze in rapporto a quanto richiesto dal contesto assistenziale e ricercare il confronto con il tutor e con la letteratura scientifica pertinente.",
        "item_11_2": "11.2 - Identificare i propri punti di forza e di debolezza in relazione al livello di competenza raggiunto rispetto ai risultati attesi e al contesto",
        "item_12_1": "12.1 - Identificare in autonomia e/o in collaborazione con il tutor clinico attività finalizzate al miglioramento delle proprie performance professionali.",
        "item_12_2": "12.2 - Documentare il percorso di apprendimento svolto attraverso l'elaborazione di specifici report.",
        "item_12_3": "12.3 - Confrontare in modo critico il proprio agire professionale con gli standard esistenti in letteratura.",
        "item_13_1": "13.1 - Orientare lo studente in tirocinio all'interno del contesto di cura rispetto alle caratteristiche delle persone assistite, al personale, all'organizzazione del servizio.",
        "item_13_2": "13.2 - Programmare con il tutor clinico e l'infermiere guida le attività degli studenti a lui affidati sulla base dei loro bisogni formativi e dell'offerta formativa del servizio."
    }
    return fieldsAndLabels[field] || field;
}

/**
 * @param {string} field
 *
 * @returns {string}
 *
 * @todo load dynamically from configuration or database
 */
function getFieldHelpText(field) {
    var fieldsAndHelpTexts = {
        "end_date": "YYYY-MM-DD format",
        "start_date": "YYYY-MM-DD format",
        "nationality": "2-letter ISO country code format",
    }
    return fieldsAndHelpTexts[field] || "";
}

window.onerror = function (msg, url, lineNo, columnNo, error) {
    alert(msg + " (" + url + ":" + lineNo + ")");
    return false;
}
