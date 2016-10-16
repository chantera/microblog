module.exports = (robot) ->
  robot.hear /hi/, (res) ->
    res.send res.random [
      'hello',
      'what?'
    ]

  robot.enter (res) ->
    res.send "Hi, #{res.message.user.name}"

  robot.leave (res) ->
    res.send "Goodbye, #{res.message.user.name}"

  robot.respond /post (.+)/i, (res) ->
    message = res.match[1]
    sqlite3 = require 'sqlite3'
    db = new (sqlite3.Database)('../db/app.db')
    db.serialize ->
      stmt = db.prepare('INSERT INTO message (body, user, post_date) VALUES (?, ?, CURRENT_TIMESTAMP)')
      stmt.run message, res.message.user.name
      stmt.finalize()
      res.reply "Post your message successfully"
      return
    db.close()

  robot.respond /select/i, (res) ->
    sqlite3 = require 'sqlite3'
    db = new (sqlite3.Database)('../db/app.db')
    db.serialize ->
      db.each 'SELECT * FROM message WHERE status=1 ORDER BY id DESC', (err, row) ->
        res.send row.id + ': ' + row.user + ', ' + row.body + ', ' + row.post_date
        return
      return
    db.close()

  robot.respond /delete (\d+)/i, (res) ->
    id = res.match[1]
    sqlite3 = require 'sqlite3'
    db = new (sqlite3.Database)('../db/app.db')
    db.serialize ->
      stmt = db.prepare('UPDATE message SET status=2 WHERE id=?')
      stmt.run id
      stmt.finalize()
      res.send "Delete your message successfully"
      return
    db.close()
