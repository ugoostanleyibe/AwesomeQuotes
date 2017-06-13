SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE `quotes` (
  `id` int(11) NOT NULL,
  `text` longtext NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

INSERT INTO `quotes` (`id`, `text`) VALUES
(1, 'There Is No Elevator To Success - You Have To Take The Stairs'),
(2, 'All That Glitters Is Not Gold'),
(3, 'To Err Is Human; To Forgive, Divine'),
(4, 'The Greatest Prison People Live In Is The Fear Of What Other People Think'),
(5, 'A Stitch In Time Saves Nine'),
(6, 'Common Sense Is A Flower That Does Not Grow In Everyone\'s Garden'),
(7, 'Trust Is Like Glass: Once Broken, It\'ll Never Be The Same Again'),
(8, 'An Intellectual Is A Person Who Has Discovered Something More Interesting Than Sex'),
(9, 'Two Things Are Infinite: The Universe And Human Stupidity'),
(10, 'Power Intoxicates; Absolute Power, Absolutely'),
(11, 'Honesty Is The Best Policy'),
(12, 'Where There\'s A Will, There\'s A Way');

ALTER TABLE `quotes`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `quotes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;