
/* create table_posts

  posts_category_created_index - сортировка небольшого результируещго набора происходит быстро,
  но в том случае если запрос найдет много строк, добавим спец. индекс с низкой селективностью.
*/

CREATE TABLE `posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `category` enum('news','people','photo','video') NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `content` varchar(242) NOT NULL,
  `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_id_uindex` (`id`),
  KEY `posts_category_created_index` (`category`,`created`)
) ENGINE=InnoDB

/* create table_likes

  likes_user_id_post_id_index - для просмотра списка "лайкнувших".
*/
CREATE TABLE `likes` (
  `post_id` bigint(20) unsigned NOT NULL,
  `user_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`post_id`,`user_id`),
  UNIQUE KEY `likes_post_id_user_id_uindex` (`post_id`,`user_id`),
  KEY `likes_user_id_post_id_index` (`user_id`,`post_id`)
) ENGINE=InnoDB

/* show posts */
SELECT id, category, user_id, content, created FROM posts WHERE category <> '' ORDER BY created DESC LIMIT 50;

/* для случая, если подразумевается разбиение на страницы, и страница находится далеко от начала.
   Из-за большого смещения, запрос потратит много времени на просмотр значительного кол-ва строк,
   которые потом будут отброшены. Используем покрывающий индекс с целью получения первичных ключей
   строк, которые нужны в итоге. Затем соеденим их с таблицей для извлечения нужных столбцов.
 */
SELECT id, category, user_id, content, created FROM posts INNER JOIN ( SELECT id FROM posts WHERE category <> '' ORDER BY created DESC LIMIT 50000, 50 ) AS x USING (id);

/* sort posts for category (news, people, photo, video)*/
SELECT id, user_id, content, created FROM posts WHERE category = 'video' ORDER BY created DESC LIMIT 50;

/* add post */
INSERT INTO posts (category, user_id, content) VALUES ('news', ?, 'hello world!');

/* show likes */
SELECT users.name FROM likes JOIN users ON likes.user_id = users.id WHERE likes.post_id = ?;

/* like post */
INSERT INTO likes (post_id, user_id) VALUES (?, ?);

/* unlike post */
DELETE FROM likes WHERE post_id = ? AND user_id = ?;