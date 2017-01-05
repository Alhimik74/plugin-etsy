
# Etsy Plugin Userguide

<div class="container-toc"></div>

## Bei Etsy registrieren

**Etsy** ist ein amerikanischer Marktplatz für den Kauf und Verkauf von handgemachten Produkten, Vintage und Künstlerbedarf. Um das Plugin für Etsy einzurichten, registrieren Sie sich zunächst als Händler bei Etsy. Sie erhalten die nötigen Zugangsdaten, die Sie für die Einstellungen in plentymarkets benötigen.

## Erste Schritte und Anforderungen

In diesem Schritt kopieren Sie das Git Repository in Ihre plentymarkets Inbox. Dafür benötigen Sie die Remote-URL von GitHub sowie Ihre Logindaten.

1. Öffnen Sie das Menü **Start » Plugins**.
2. Klicken Sie auf **Add plugin**.
→ Das neue Plugin-Fenster wird geöffnet.
3. Klicken Sie auf **Git**.
→ Das Fenster **Settings** wird geöffnet.
4. Tragen Sie die Remote-URL ein.
→ Sie können die URL mit einem Klick auf Clone oder download in der Repository auf Github kopieren.
5. Tragen Sie Ihren User-Namen und das Password ein.
6. Klicken SIe auf **Test connection**.
→ Die Verbindung zu dem Git Repository wird überprüft und hergestellt und das Dropdown-Menü Branch kann gewählt werden.
7. Wählen Sie den Branch des Repository, das Sie kopieren und bearbeiten möchten.
8. Speichern Sie die Einstellungen.
→ Das Plugin Repository wurde in Ihre plentymarkets Inbox kopiert und das Plugin zu der Plugin-Liste hinzugefügt.


## Etsy in plentymarkets einrichten

Um Artikel auf Etsy anzubieten, richten Sie Etsy in plentymarkets ein. Gehen Sie dazu wie im Folgenden beschrieben vor.

## Artikelverfügbarkeit einstellen

Artikel, die Sie auf Etsy verkaufen möchten, müssen im Menü **Artikel » Artikel bearbeiten » Artikel öffnen » Tab: Varianten-ID** im Tab **Verfügbarkeit** aktiviert werden.

##### Artikelverfügbarkeit für Etsy einstellen:

1. Öffnen Sie das Menü **Artikel » Artikel bearbeiten » Artikel öffnen » Tab: Varianten-ID » Tab: Einstellungen**.
2. Aktivieren Sie die Hauptvariante im Bereich **Verfügbarkeit**.
3. Wechseln Sie in das Tab **Verfügbarkeit**.
4. Klicken Sie im Bereich **Märkte** in das Auswahlfeld.
    → Eine Liste mit allen verfügbaren Märkten wird angezeigt.
5. Aktivieren Sie die Option **Etsy**.
6. Klicken Sie auf **Hinzufügen**.
    → Der Marktplatz wird hinzugefügt.
7. **Speichern** Sie die Einstellungen.
    → Der Artikel ist auf Etsy verfügbar.

Die Verfügbarkeit für Varianten kann im Menü **Artikel » Artikel bearbeiten » Artikel öffnen » Tab: Varianten » Variante öffnen » Tab: Varianten-ID » Tab: Verfügbarkeit** individuell angepasst werden.

## Verkaufspreis festlegen

Gehen Sie wie im Folgenden beschrieben vor, um für die Auftragsherkunft Etsy einen Verkaufspreis festzulegen. Dieser Preis wird auf Etsy angezeigt. 

##### Verkaufspreise für Etsy festlegen:

1. Öffnen Sie das Menü **Einstellungen » Artikel » Verkaufspreise » Verkaufspreis öffnen » Tab: Einstellungen**.
2. Setzen Sie ein Häkchen bei der Herkunft **Etsy**.
3. **Speichern** Sie die Einstellungen.

## Kategorien verknüpfen

Verknüpfen Sie Ihre Webshop-Kategorien mit den Kategorien von Etsy, damit Ihre Artikel in diesen Etsy-Kategorien angezeigt werden. Weitere Artikel der verknüpften Kategorien werden dann automatisch zugewiesen.

##### Kategorien verknüpfen:

1. Öffnen Sie das Menü **Einstellungen » Märkte » Etsy » Kategorieverknüpfung**.
2. Klicken Sie auf **Suchen**.
    → Das Fenster **Kategorie wählen** wird geöffnet.
3. Wählen Sie die Etsy-Kategorie, die am besten zu Ihrer Webshop-Kategorie passt.
4. Klicken Sie auf **Übernehmen**.
    → Die Bezeichnung der Etsy-Kategorie und der Kategoriepfad werden angezeigt.
5. Wenn Sie die Bezeichnung der Etsy-Kategorie bereits kennen, geben Sie sie in das Feld **Marktplatzkategorie** ein, um sie mit Ihrer Webshop-Kategorie zu verknüpfen.
6. **Speichern** Sie die Einstellungen.

## Merkmale verknüpfen

Um Merkmale für den Marktplatz Etsy zu nutzen, verknüpfen Sie diese mit Etsy.

##### Merkmale verknüpfen:

1. Öffnen Sie das Menü **Einstellungen » Märkte » Etsy » Merkmalverknüpfung**.
2. Klicken Sie auf **Suchen**.
    → Das Fenster **Merkmale wählen** wird geöffnet.
3. Wählen Sie das Etsy-Merkmal, das am besten zu Ihrem Webshop-Merkmal passt.
4. Klicken Sie auf **Übernehmen**.
    → Die Bezeichnung des Etsy-Merkmals und der Merkmalpfad werden angezeigt.
5. **Speichern** Sie die Einstellungen.

## Versandprofile verknüpfen

Im Menü **Einstellungen » Märkte » Etsy » Versandprofilverknüpfungen** verknüpfen Sie Etsy-Versandprofile mit den Versandprofilen Ihres Webshops. 

##### Versandprofile verknüpfen:

1. Öffnen Sie das Menü **Einstellungen » Märkte » Etsy » Versandprofilverknüpfung**.
2. Wählen Sie das Etsy-Versandprofil, das am besten zu Ihrem Webshop-Versandprofil passt.
3. Klicken Sie auf **Übernehmen**.
4. **Speichern** Sie die Einstellungen.

## Zahlungsbestätigung automatisch senden

Richten Sie eine Ereignisaktion ein, um Zahlungsbestätigungen automatisch an Etsy zu senden, nachdem ein Zahlungseingang gebucht wurde.

##### Ereignisaktion einrichten:

1. Öffnen Sie das Menü **Einstellungen » Aufträge » Ereignisaktionen**.
2. Klicken Sie auf **Ereignisaktion hinzufügen**.
→ Das Fenster **Neue Ereignisaktion erstellen** wird geöffnet.
3. Geben Sie einen Namen ein.
4. Wählen Sie das Ereignis gemäß Tabelle 1.
5. **Speichern** Sie die Einstellungen.
6. Nehmen Sie die Einstellungen gemäß Tabelle 1 vor.
7. Setzen Sie ein Häkchen bei **Aktiv**.
8. **Speichern** Sie die Einstellungen.

<table>
	<thead>
		<th>
			Einstellung
		</th>
		<th>
			Option
		</th>
<th>
			Auswahl
		</th>
	</thead>
	<tbody>
      <tr>
         <td><strong>Ereignis</strong></td>
         <td><strong>Zahlung: Vollständig</strong></td> 
<td></td>
      </tr>
      <tr>
         <td><strong>Filter 1</strong></td>
         <td><strong>Auftrag > Auftragstyp</strong></td>
<td><strong>Auftrag</strong></td>
      </tr>
<tr>
         <td><strong>Filter 2</strong></td>
         <td><strong>Auftrag > Herkunft</strong></td>
<td><strong>Etsy</strong></td>
      </tr>
      <tr>
         <td><strong>Aktion</strong></td>
         <td><strong>Plugin > Zahlungsbestätigung an Etsy senden</strong></td>
<td>&nbsp;</td>
      </tr>
</tbody>
	<caption>
		Table 2: Ereignisaktion zum automatischen Senden von Zahlungsbestätigungen an Etsy
	</caption>
</table>

## Versandbestätigung automatisch senden

Richten Sie eine Ereignisaktion ein, um Versandbestätigungen automatisch an Etsy zu senden, nachdem ein Warenausgang gebucht wurde.

##### Ereignisaktion einrichten:

1. Öffnen Sie das Menü **Einstellungen » Aufträge » Ereignisaktionen**.
2. Klicken Sie auf **Ereignisaktion hinzufügen**.
→ Das Fenster **Neue Ereignisaktion erstellen** wird geöffnet.
3. Geben Sie einen Namen ein.
4. Wählen Sie das Ereignis gemäß Tabelle 2.
5. **Speichern** Sie die Einstellungen.
6. Nehmen Sie die Einstellungen gemäß Tabelle 2 vor.
7. Setzen Sie ein Häkchen bei **Aktiv**.
8. **Speichern** Sie die Einstellungen.


<table>
	<thead>
		<th>
			Einstellung
		</th>
		<th>
			Option
		</th>
<th>
			Auswahl
		</th>
	</thead>
	<tbody>
      <tr>
         <td><strong>Ereignis</strong></td>
         <td><strong>Auftragsänderung: Warenausgang gebucht</strong></td> 
<td></td>
      </tr>
      <tr>
         <td><strong>Filter 1</strong></td>
         <td><strong>Auftrag > Auftragstyp</strong></td>
<td><strong>Auftrag</strong></td>
      </tr>
<tr>
         <td><strong>Filter 2</strong></td>
         <td><strong>Auftrag > Herkunft</strong></td>
<td><strong>Etsy</strong></td>
      </tr>
      <tr>
         <td><strong>Aktion</strong></td>
         <td><strong>Plugin > Versandbestätigung an Etsy senden</strong></td>
<td>&nbsp;</td>
      </tr>
</tbody>
	<caption>
		Table 2: Ereignisaktion zum automatischen Senden von Versandbestätigungen an Etsy
	</caption>
</table>