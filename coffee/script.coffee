class CloudKeys
  constructor: () ->
    @fetchData()
    @password = 'test' #todo replace with user password
    $('#search').keyup =>
      `var that = this`
      @showItems(@getItems($(that).val()))
      return
    $('#search').focus()

  import: (xml) ->
    parsedXML = $.parseXML(xml)

    entities = []
    for group in $(parsedXML).find('group')
      tag = $(group).find('>title').text()
      for entry in $(group).find('entry')
        e = $(entry)
        entity = {}
        entity[@encrypt('title')] = @encrypt(e.find('title').text())
        entity[@encrypt('username')] = @encrypt(e.find('username').text())
        entity[@encrypt('password')] = @encrypt(e.find('password').text())
        entity[@encrypt('url')] = @encrypt(e.find('url').text())
        entity[@encrypt('comment')] = @encrypt(e.find('comment').text())
        entity[@encrypt('tags')] = @encrypt(tag)
        entities.push(entity)
    #todo send it to server
    console.log entities

  fetchData: () ->
    $.get 'ajax', (data) =>
      console.log data

    , "json"

  encrypt: (value) ->
    return String(CryptoJS.AES.encrypt(value, @password))

  decrypt: (value) ->
    return CryptoJS.AES.decrypt(value, @password)

  showItems: (items) ->
    $('#items li').remove()
    itemContainer = $('#items')
    for item in items
      itemContainer.append("<li>#{ item.title } <span>#{ item.username }</span></li>")
    return

  getItems: (search) ->
    return [{'title': 'Bla bla', 'username': 'mthie'}]

window.CloudKeys = new CloudKeys()
$('#importLink').click =>
  $('#importContainer').toggle(500)

$('#importContainer button').click =>
  window.CloudKeys.import($('#import').val())
  #$('#import').val('')
