-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 21, 2024 at 07:08 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `login_system_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `author` varchar(255) NOT NULL,
  `isbn` varchar(100) NOT NULL,
  `pub_year` int(11) NOT NULL,
  `genre` varchar(255) NOT NULL,
  `availability` varchar(20) NOT NULL,
  `image_path` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `description`, `author`, `isbn`, `pub_year`, `genre`, `availability`, `image_path`) VALUES
(17, 'Harry Potter and the Sorcerer\'s stone', 'The story follows a young wizard, Harry Potter, who discovers his magical heritage on his eleventh birthday.', 'J.K. Rowling', '978-0590353427', 1997, 'Fantasy', 'Not Available', 0x75706c6f6164732f6861727279706f747465722e6a7067),
(21, 'The Catcher in the rye', 'The story is narrated by Holden Caulfield, a disenchanted and rebellious teenager who has been expelled from an elite boarding school.', 'J.D. Salinger', '978-0316769488', 1978, 'Fiction, Coming-of-Age', 'Not Available', 0x75706c6f6164732f636174636865722d696e2d7468652d7279652d636f7665722d696d6167652d36383278313032342e6a706567),
(22, 'The Lord of the Rings', 'The epic tale unfolds in the fictional world of Middle-earth, where various races, including hobbits, elves, dwarves, men, and wizards, become embroiled in the quest to destroy the One Ring, a powerful and malevolent artifact created by the dark lord Sauron', 'J.R.R. Tolkien', '978-0544003415', 1954, 'Fantasy', 'Available', 0x75706c6f6164732f7468652d6c6f72642d6f662d7468652d72696e67732d626f6f6b2d636f7665722e6a7067),
(23, 'Pride and Prejudice', 'The story is set in early 19th-century England and revolves around the life of Elizabeth Bennet, one of five sisters from the respectable Bennet family. The novel explores themes of love, class, and societal expectations.', 'Jane Austen', '978-0141439518', 1813, 'Fiction, Classic, Romance', 'Not Available', 0x75706c6f6164732f70726964652d616e642d7072656a75646963652d37312e6a7067),
(24, 'The Hobbit', 'is a captivating fantasy adventure that follows Bilbo Baggins, a reluctant hobbit hero, on a quest to reclaim a dwarf kingdom. Filled with magical creatures, epic battles, and the discovery of inner courage, it\'s a timeless tale of bravery and friendship in the enchanting world of Middle-earth.', 'J.R.R. Tolkien', '978-0547928227', 1937, 'Fantasy', 'Not Available', 0x75706c6f6164732f74686520686f626269742e6a7067),
(25, 'The Hunger Games', 'Set in a future society, the story follows Katniss Everdeen as she navigates a televised fight to the death, representing her district in a brutal annual event called the Hunger Games. The narrative explores themes of survival, rebellion, and the consequences of a power-hungry government.', 'Suzanne Collins', '978-0439023481', 2008, 'Young Adult, Dystopian', 'Available', 0x75706c6f6164732f7468652068756e6765722067616d65732e6a7067),
(26, 'The Da Vinci Code', 'The story revolves around Harvard symbologist Robert Langdon as he investigates a murder at the Louvre Museum in Paris. Filled with hidden codes, symbols, and historical secrets, the novel takes readers on a thrilling journey through art, history, and religious intrigue.', 'Dan Brown', '978-0307474278', 2003, 'Mystery, Thriller', 'Not Available', 0x75706c6f6164732f7468652d64612d76696e63692d636f64652e6a7067),
(33, 'A Game of Thrones', 'The story unfolds in the fictional continents of Westeros and Essos, where noble families vie for control of the Iron Throne and the Seven Kingdoms. Filled with political intrigue, power struggles, and unexpected twists, the book introduces readers to a vast and immersive world with memorable characters and a gripping narrative.', 'George R.R. Martin', '978-895152732', 1996, 'Fantasy', 'Not Available', 0x75706c6f6164732f676f742e6a7067),
(34, 'The Chronicles of Narnia', 'The series follows the adventures of children who are magically transported to the world of Narnia, where they encounter talking animals, mythical creatures, and epic battles between good and evil.', 'C.S. Lewis', '978-566884303', 1950, 'Fantasy, Children\'s', 'Not Available', 0x75706c6f6164732f6e61726e69612e6a7067),
(35, 'Noli Me Tangere', 'The title translates to \"Touch Me Not\" in English. This classic work explores the injustices and societal issues prevalent during the Spanish colonial period in the Philippines, exposing corruption, abuse of power, and the struggle for national identity.', 'Jose Rizal', '978-533703972', 1887, 'Fiction, Classic', 'Not Available', 0x75706c6f6164732f6e6f6c69206d652074616e676572652e6a7067),
(36, 'El Filibusterismo', 'The title translates to \"The Subversive\" or \"The Filibustering\" in English. This novel delves deeper into the issues of social injustice, corruption, and abuse of power during the Spanish colonial era in the Philippines.', 'Jose Rizal', '978-985906966', 1891, 'Fiction, Classic', 'Not Available', 0x75706c6f6164732f656c2066696c692e6a7067),
(39, 'Naruto Volume 1: Uzumaki Naruto', 'The story follows Naruto Uzumaki, a young ninja with dreams of becoming the strongest ninja and earning the title of Hokage, the leader of his village.', 'Masashi Kishimoto', '978-865252755', 2003, 'Shonen, Action, Adventure', 'Not Available', 0x75706c6f6164732f6e617275746f2d766f6c2d312e6a7067),
(40, 'One Piece Volume 1: Romance Dawn', 'marks the beginning of an epic adventure created by Eiichiro Oda. The story follows Monkey D. Luffy, a young and ambitious pirate with the dream of finding the legendary treasure known as One Piece and becoming the Pirate King.', 'Eiichiro Oda', '978-750763248', 2003, 'Shonen, Action, Adventure', 'Available', 0x75706c6f6164732f6f702d726f6d616e63652d6461776e2e6a7067),
(41, 'Attack on Titan Volume 1: The Fall of Shiganshina', 'In a world besieged by giant humanoid creatures known as Titans, humanity seeks refuge within enormous walled cities. Eren Yeager, the protagonist, witnesses the sudden breach of the outer wall, leading to catastrophic consequences.', 'Hajime Isayama', '978-781830480', 2012, 'Shonen, Dark Fantasy, Action', 'Not Available', 0x75706c6f6164732f5368696e67656b695f6e6f5f4b796f6a696e5f6d616e67615f766f6c756d655f312e6a7067),
(42, 'Death Note Volume 1: Boredom', 'introduces readers to the enthralling world of Light Yagami, a highly intelligent high school student who discovers a mysterious notebook with deadly powers.', 'Tsugumi Ohba', '978-327638769', 2005, 'Shonen, Psychological Thriller, Mystery', 'Not Available', 0x75706c6f6164732f64656174686e6f74652e6a7067),
(43, 'Fullmetal Alchemist Volume 1: The Land of Sand', 'After a failed alchemical experiment to bring their deceased mother back to life, Edward loses his left leg, and Alphonse loses his entire body. In a desperate attempt to save his brother, Edward sacrifices his right arm to bind Alphonse\'s soul to a suit of armor.', 'Hiromu Arakawa', '978-692597068', 2005, 'Shonen, Adventure, Fantasy', 'Available', 0x75706c6f6164732f666d612e6a7067),
(44, 'Dragon Ball Volume 1', 'The story follows Goku, a young and naive boy with a monkey\'s tail, as he sets out on a journey to find the Dragon Balls, powerful orbs that can grant any wish.', 'Akira Toriyama', '978-977272956', 2003, 'Shonen, Action, Adventure', 'Not Available', 0x75706c6f6164732f647261676f6e2d62616c6c2d766f6c2d312d736a2d65646974696f6e2e6a7067),
(45, 'My Hero Academia Volume 1: Izuku Midoriya: Origin', 'The story unfolds in a world where individuals possess superpowers known as \"Quirks,\" and it follows the journey of Izuku Midoriya, a Quirkless boy with aspirations of becoming a hero.', 'Kohei Horikoshi', '978-424553775', 2015, 'Shonen, Superhero, Action', 'Available', 0x75706c6f6164732f6d68612076312e6a7067),
(46, 'Demon Slayer: Kimetsu no Yaiba Volume 1: Cruelty', 'The story follows Tanjiro Kamado, a young boy whose life takes a tragic turn when his family is slaughtered by demons, and his sister Nezuko is turned into one.', 'Koyoharu Gotouge', '978-653106549', 2018, 'Shonen, Action, Dark Fantasy', 'Not Available', 0x75706c6f6164732f64656d6f6e2d736c617965722d6b696d657473752d6e6f2d79616962612d766f6c2d312e6a7067),
(48, 'Haikyu!! Volume 1: Hinata and Kageyama', 'The story revolves around Shoyo Hinata, a determined and vertically challenged athlete inspired by a legendary player known as the \"Little Giant.\" Despite facing challenges due to his height, Hinata joins the Karasuno High School volleyball team with unwavering enthusiasm.', 'Haruichi Furudate', '978-4088806948', 2012, 'Shonen, Sports, Volleyball', 'Not Available', 0x75706c6f6164732f6861696b79752e6a7067),
(49, 'Black Clover Volume 1: The Boy\'s Vow', 'The story takes an exciting turn when Asta receives a unique grimoire that grants him an anti-magic sword. Alongside his childhood friend Yuno, who is exceptionally talented in magic, Asta embarks on a journey to fulfill his dream.', 'Yūki Tabata', '978-574544535', 2015, 'Shonen, Fantasy, Magic', 'Not Available', 0x75706c6f6164732f626c61636b636c6f7665722e6a7067),
(76, 'It', 'A terrifying entity preys on the children of Derry, Maine, resurfacing every 27 years.', 'Stephen King', '978-134703224', 1986, 'Supernatural Horror', 'Not Available', 0x75706c6f6164732f49745f2831393836295f66726f6e745f636f7665722c5f66697273745f65646974696f6e2e6a7067),
(79, 'The Shining', 'A psychological horror novel about a writer who takes his family to an isolated hotel for the winter, where supernatural forces begin to take hold.', 'Stephen King', '978-845126051', 1978, 'Horror Psychological Thriller', 'Not Available', 0x75706c6f6164732f746865207368696e696e672e6a7067),
(80, 'Batman: The Killing Joke', '\"The Killing Joke\" explores the complex relationship between Batman and his arch-nemesis, the Joker. The story delves into the Joker\'s origin, presenting him as a failed comedian who turns to crime after a series of tragic events.', 'Alan Moore', '978-751350093', 1988, 'Superhero, Psychological thriller', 'Available', 0x75706c6f6164732f6261746d616e2e6a7067),
(81, 'The Great Gatsby', '\"The Great Gatsby\" is a novel set in the 1920s, depicting the Jazz Age and the Roaring Twenties in America. It follows the story of Jay Gatsby, a wealthy and mysterious man, and his pursuit of the American Dream. The book explores themes of love, wealth, decadence, and the emptiness of the pursuit of material success.', 'F. Scott Fitzgeraldd', '978-598445512', 1926, 'Fiction, Classic Literature', 'Not Available', 0x75706c6f6164732f5468655f47726561745f4761747362795f436f7665725f313932355f5265746f75636865642e6a7067),
(87, 'Stupid is Forever', 'A compilation of witty and humorous essays, anecdotes, and speeches by the renowned Philippine senator.', 'Miriam Defensor Santiago', '978-406826823', 2014, 'Humor/Politics', 'Available', 0x75706c6f6164732f73747570696420697320666f72657665722e6a7067),
(88, 'The Lion, the Witch and the Wardrobe', 'The first book in The Chronicles of Narnia series, it follows the adventures of four siblings who discover the magical land of Narnia and join the fight against the White Witch.', 'C.S. Lewis', '978-948777154', 1950, 'Fantasy, Children\'s Literature', 'Available', 0x75706c6f6164732f7468652d6c696f6e2d7468652d77697463682d616e642d7468652d77617264726f62652d312e6a7067),
(89, '1984', 'A dystopian novel set in a totalitarian society where individuality and independent thought are suppressed by the ruling Party led by Big Brother.', 'George Orwell', '978-583411954', 1949, 'Dystopian Fiction, Political Satire', 'Not Available', 0x75706c6f6164732f313938342e6a7067),
(90, 'The Help', 'Set in 1960s Mississippi, the novel tells the story of African American maids who work for white families and the unlikely friendships that form amidst racial tensions and social injustices.', 'Kathryn Stockett', '978-821590241', 2009, 'Historical Fiction, Drama', 'Available', 0x75706c6f6164732f7468652068656c702e6a7067),
(91, 'Watchmen', 'Set in an alternate history where superheroes are real, \"Watchmen\" explores complex themes of power, morality, and human nature.', 'Alan Moore', '978-407028204', 1986, 'Superhero, Mystery, Drama', 'Not Available', 0x75706c6f6164732f57617463686d656e2c5f69737375655f312e6a7067),
(92, 'X-Men: Days of Future Past', 'In a dystopian future, mutants are hunted to near-extinction, prompting the X-Men to send Wolverine back in time to prevent the catastrophic events that led to this timeline.', 'Chris Claremont', '978-196028760', 1981, 'Superhero, Science Fiction, Time Travel', 'Available', 0x75706c6f6164732f786d656e2e6a7067),
(93, 'The Structure of Scientific Revolutions', 'This seminal work explores the process of scientific revolutions and paradigm shifts.', 'Thomas S. Kuhn', '978-733645177', 1962, 'Philosophy of Science', 'Not Available', 0x75706c6f6164732f746865207374727563747572652e6a7067),
(94, 'Capital in the Twenty-First Century', 'Piketty examines the dynamics of wealth and income inequality in capitalist economies.', 'Thomas Piketty', '978-234847473', 2013, 'Economics', 'Available', 0x75706c6f6164732f6361706974616c2d696e2d7468652d7477656e74792d66697273742d63656e747572792d332e6a7067),
(95, 'Guns, Germs, and Steel: The Fates of Human Societies', 'Diamond explores the factors that have shaped human history, including geography, agriculture, and technology.', 'Jared Diamond', '978-537225273', 1997, 'Anthropology/History', 'Not Available', 0x75706c6f6164732f67756e732d6765726d732d616e642d737465656c2d7468652d66617465732d6f662d68756d616e2d736f636965746965732d323074682d616e6e69766572736172792d65646974696f6e2e6a7067),
(97, 'The Impact of Climate Change on Agricultural Practices', 'This thesis explores the effects of climate change on modern agricultural practices, analyzing data from various regions and proposing sustainable solutions.', 'John Smith', '978-924130200', 2021, 'Environmental Science', 'Not Available', 0x75706c6f6164732f696d6167657320283130292e6a706567),
(98, 'Thinking, Fast and Slow', 'Kahneman explores the two systems of thinking that drive human decision-making.', 'Daniel Kahneman', '978-722323128', 2011, 'Psychology/Behavioral Economics', 'Not Available', 0x75706c6f6164732f7468696e6b696e672d666173742d616e642d736c6f772e6a7067);

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE `borrowed_books` (
  `borrow_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `borrow_date` date DEFAULT NULL,
  `return_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowed_books`
--

INSERT INTO `borrowed_books` (`borrow_id`, `user_id`, `book_id`, `borrow_date`, `return_date`) VALUES
(8, 19, 17, '2024-03-04', '2024-03-23'),
(12, 20, 21, '2024-03-04', '2024-05-16'),
(13, 19, 24, '2024-03-04', '2024-03-30'),
(14, 19, 26, '2024-03-04', '2024-03-23'),
(15, 21, 46, '2024-03-04', '2024-04-30'),
(16, 21, 43, '2024-03-04', '2024-04-18'),
(18, 20, 45, '2024-03-05', '2024-03-31'),
(19, 21, 44, '2024-03-05', '2024-03-23'),
(20, 21, 44, '2024-03-05', '2024-03-30'),
(21, 21, 23, '2024-03-05', '2024-03-16'),
(22, 21, 22, '2024-03-05', '2024-03-30'),
(23, 20, 25, '2024-03-05', '2024-03-30'),
(25, 22, 33, '2024-03-05', '2024-05-25'),
(26, 22, 34, '2024-03-05', '2024-03-30'),
(27, 22, 35, '2024-03-05', '2024-03-30'),
(29, 19, 17, '2024-03-05', '2024-03-30'),
(32, 21, 46, '2024-03-05', '2027-03-31'),
(34, 19, 21, '2024-03-05', '2024-03-31'),
(35, 19, 49, '2024-03-05', '2024-03-31'),
(36, 22, 17, '2024-03-05', '2024-03-31'),
(37, 22, 48, '2024-03-05', '2024-03-30'),
(38, 22, 43, '2024-03-05', '2024-04-27'),
(39, 23, 24, '2024-03-05', '2024-03-30'),
(40, 23, 26, '2024-03-05', '2024-03-30'),
(41, 23, 40, '2024-03-05', '2024-03-30'),
(43, 20, 36, '2024-03-05', '2024-03-30'),
(44, 21, 42, '2024-03-05', '2024-03-23'),
(45, 21, 41, '2024-03-05', '2024-03-30'),
(47, 22, 39, '2024-03-05', '2024-03-30'),
(51, 22, 33, '2024-03-06', '2024-03-22'),
(52, 22, 17, '2024-03-06', '2024-03-22'),
(53, 22, 24, '2024-03-06', '2024-03-26'),
(54, 22, 21, '2024-03-06', '2024-03-30'),
(55, 22, 17, '2024-03-06', '2024-03-11'),
(57, 22, 21, '2024-03-06', '2024-03-21'),
(58, 22, 24, '2024-03-06', '2024-03-30'),
(59, 22, 26, '2024-03-06', '2024-03-30'),
(60, 22, 34, '2024-03-06', '2024-03-30'),
(61, 22, 33, '2024-03-06', '2024-03-30'),
(62, 22, 35, '2024-03-06', '2024-03-30'),
(63, 22, 24, '2024-03-06', '2024-03-21'),
(64, 22, 26, '2024-03-06', '2024-03-30'),
(65, 22, 17, '2024-03-06', '2024-03-08'),
(66, 22, 35, '2024-03-06', '2024-03-30'),
(67, 22, 49, '2024-03-06', '2024-03-30'),
(68, 22, 17, '2024-03-06', '2024-03-22'),
(69, 19, 17, '2024-03-07', '2024-03-23'),
(72, 21, 49, '2024-03-07', '2024-03-30'),
(73, 21, 40, '2024-03-07', '2024-03-30'),
(74, 21, 45, '2024-03-07', '2024-03-30'),
(75, 21, 46, '2024-03-07', '2024-03-29'),
(77, 20, 21, '2024-03-07', '2024-03-23'),
(78, 20, 22, '2024-03-07', '2024-03-30'),
(79, 20, 48, '2024-03-07', '2024-03-30'),
(80, 22, 41, '2024-03-07', '2024-03-30'),
(81, 22, 42, '2024-03-07', '2024-03-30'),
(82, 20, 34, '2024-03-07', '2024-03-27'),
(83, 19, 23, '2024-03-07', '2024-03-30'),
(84, 19, 43, '2024-03-07', '2025-08-22'),
(85, 19, 44, '2024-03-07', '2026-04-16'),
(86, 23, 17, '2024-03-07', '2026-02-28'),
(87, 23, 24, '2024-03-07', '2024-03-30'),
(88, 23, 25, '2024-03-07', '2024-03-30'),
(89, 20, 26, '2024-03-07', '2024-03-30'),
(90, 21, 17, '2024-03-08', '2024-03-30'),
(92, 19, 46, '2024-03-08', '2024-03-30'),
(95, 20, 21, '2024-03-08', '2024-03-30'),
(96, 20, 22, '2024-03-08', '2024-03-30'),
(97, 20, 48, '2024-03-08', '2024-03-30'),
(99, 22, 23, '2024-03-08', '2027-07-31'),
(101, 19, 24, '2024-03-08', '2024-10-26'),
(102, 19, 42, '2024-03-08', '2024-03-30'),
(103, 21, 33, '2024-03-08', '2024-10-31'),
(106, 21, 17, '2024-03-10', '2024-07-19'),
(109, 21, 48, '2024-03-10', '2024-03-30'),
(110, 21, 45, '2024-03-10', '2024-03-23'),
(111, 21, 48, '2024-03-10', '2024-03-30'),
(112, 21, 39, '2024-03-10', '2024-03-30'),
(114, 20, 17, '2024-03-10', '2024-03-30'),
(115, 20, 41, '2024-03-10', '2024-03-29'),
(116, 20, 23, '2024-03-10', '2024-03-29'),
(117, 22, 24, '2024-03-11', '2024-03-30'),
(118, 22, 43, '2024-03-11', '2024-03-30'),
(121, 22, 17, '2024-03-15', '2024-03-31'),
(122, 22, 76, '2024-03-15', '2024-03-30'),
(123, 20, 21, '2024-03-15', '2024-03-30'),
(124, 19, 22, '2024-03-16', '2024-03-30'),
(125, 22, 49, '2024-03-16', '2024-03-30'),
(126, 22, 79, '2024-03-16', '2024-03-30'),
(127, 22, 23, '2024-03-16', '2024-03-30'),
(128, 21, 23, '2024-03-16', '2024-04-30'),
(129, 21, 41, '2024-03-16', '2024-07-18'),
(130, 23, 25, '2024-03-16', '2024-03-30'),
(131, 20, 26, '2024-03-16', '2024-03-30'),
(132, 20, 33, '2024-03-16', '2024-07-20'),
(133, 20, 35, '2024-03-16', '2024-03-30'),
(134, 19, 45, '2024-03-16', '2024-03-30'),
(135, 19, 24, '2024-03-17', '2024-11-30'),
(136, 19, 48, '2024-03-17', '2024-03-30'),
(137, 21, 39, '2024-03-17', '2025-02-28'),
(138, 19, 17, '2024-03-17', '2024-03-30'),
(139, 19, 21, '2024-03-17', '2024-03-30'),
(140, 20, 80, '2024-03-17', '2024-05-17'),
(141, 19, 79, '2024-03-17', '2024-03-30'),
(142, 19, 76, '2024-03-17', '2024-03-30'),
(143, 20, 23, '2024-03-17', '2027-02-17'),
(144, 19, 76, '2024-03-17', '2024-03-30'),
(145, 19, 79, '2024-03-17', '2024-03-30'),
(146, 19, 81, '2024-03-17', '2024-03-29'),
(147, 19, 79, '2024-03-17', '2024-03-30'),
(148, 19, 81, '2024-03-17', '2024-03-30'),
(149, 19, 81, '2024-03-17', '2024-03-28'),
(150, 19, 76, '2024-03-17', '2024-03-30'),
(151, 19, 79, '2024-03-17', '2024-03-30'),
(152, 19, 81, '2024-03-17', '2024-03-30'),
(153, 19, 79, '2024-03-17', '2024-10-26'),
(154, 21, 49, '2024-03-17', '2024-03-30'),
(155, 21, 17, '2024-03-17', '2024-03-30'),
(156, 19, 81, '2024-03-17', '2024-07-27'),
(157, 23, 80, '2024-03-17', '2024-03-30'),
(158, 23, 81, '2024-03-17', '2024-04-30'),
(159, 23, 17, '2024-03-17', '2024-03-30'),
(160, 23, 21, '2024-03-17', '2024-03-19'),
(161, 19, 79, '2024-03-17', '2024-03-30'),
(162, 20, 49, '2024-03-17', '2033-03-31'),
(163, 19, 79, '2024-03-17', '2024-04-04'),
(164, 20, 76, '2024-03-18', '2024-03-19'),
(165, 20, 76, '2024-03-18', '2024-03-30'),
(166, 20, 23, '2024-03-18', '2024-03-30'),
(167, 20, 49, '2024-03-18', '2024-03-30'),
(168, 21, 49, '2024-03-18', '2024-12-21'),
(169, 20, 22, '2024-03-18', '2024-03-31'),
(171, 20, 87, '2024-03-18', '2024-03-30'),
(172, 20, 91, '2024-03-18', '2024-03-23'),
(173, 20, 92, '2024-03-19', '2024-04-06'),
(174, 21, 91, '2024-03-19', '2024-03-30'),
(176, 19, 41, '2024-03-19', '2024-04-19'),
(177, 19, 24, '2024-03-19', '2024-04-27'),
(178, 19, 95, '2024-03-19', '2024-03-22'),
(179, 26, 94, '2024-03-19', '2024-06-22'),
(180, 26, 17, '2024-03-19', '2024-05-25'),
(181, 26, 22, '2024-03-19', '2024-04-30'),
(182, 26, 36, '2024-03-19', '2024-06-29'),
(183, 20, 97, '2024-03-19', '2024-03-27'),
(185, 20, 35, '2024-03-19', '2024-03-30'),
(186, 23, 76, '2024-03-19', '2024-03-30'),
(187, 23, 23, '2024-03-19', '2024-03-29'),
(188, 23, 21, '2024-03-19', '2024-03-23'),
(190, 23, 24, '2024-03-19', '2024-03-30'),
(191, 20, 98, '2024-03-20', '2024-03-30'),
(192, 20, 21, '2024-03-20', '2024-05-02'),
(193, 19, 89, '2024-03-20', '2024-06-27'),
(194, 19, 95, '2024-03-20', '2024-11-02'),
(195, 19, 97, '2024-03-20', '2024-03-30'),
(196, 21, 90, '2024-03-20', '2024-03-30'),
(197, 20, 21, '2024-03-20', '2024-03-30'),
(198, 20, 93, '2024-03-20', '2024-07-27'),
(199, 20, 95, '2024-03-20', '2024-03-28'),
(200, 20, 91, '2024-03-20', '2024-06-15'),
(201, 20, 42, '2024-03-20', '2024-06-29'),
(202, 21, 39, '2024-03-20', '2024-07-27'),
(203, 21, 44, '2024-03-20', '2024-06-15'),
(204, 21, 79, '2024-03-20', '2024-06-29'),
(205, 23, 33, '2024-03-20', '2024-09-28'),
(206, 23, 24, '2024-03-20', '2024-03-30'),
(207, 23, 41, '2024-03-20', '2024-03-30'),
(208, 19, 98, '2024-03-20', '2024-06-29'),
(209, 19, 76, '2024-03-20', '2024-05-25'),
(210, 19, 34, '2024-03-20', '2024-03-30'),
(211, 26, 35, '2024-03-20', '2024-03-30'),
(212, 26, 81, '2024-03-20', '2024-03-30'),
(213, 26, 23, '2024-03-20', '2024-03-23'),
(214, 26, 36, '2024-03-20', '2024-08-31'),
(215, 19, 49, '2024-03-20', '2024-06-22'),
(216, 22, 26, '2024-03-20', '2024-06-01'),
(217, 22, 46, '2024-03-20', '2024-03-30'),
(218, 22, 17, '2024-03-20', '2024-05-25'),
(219, 22, 89, '2024-03-20', '2024-04-27'),
(220, 22, 48, '2024-03-20', '2024-05-31'),
(221, 19, 97, '2024-03-21', '2024-03-30');

-- --------------------------------------------------------

--
-- Table structure for table `return_history`
--

CREATE TABLE `return_history` (
  `return_id` int(11) NOT NULL,
  `borrow_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `book_id` int(11) DEFAULT NULL,
  `returned_date_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `return_history`
--

INSERT INTO `return_history` (`return_id`, `borrow_id`, `user_id`, `book_id`, `returned_date_time`, `status`) VALUES
(1, 15, 21, 46, '2024-03-05 09:52:28', 'returned'),
(2, 19, 21, 44, '2024-03-05 10:00:04', 'returned'),
(3, 16, 21, 43, '2024-03-05 10:05:41', 'returned'),
(4, 12, 20, 21, '2024-03-05 11:56:25', 'returned'),
(6, 8, 19, 17, '2024-03-05 12:00:52', 'returned'),
(9, 13, 19, 24, '2024-03-05 12:01:06', 'returned'),
(10, 14, 19, 26, '2024-03-05 12:01:08', 'returned'),
(12, 29, 19, 17, '2024-03-05 14:14:44', 'returned'),
(14, 39, 23, 24, '2024-03-05 17:37:37', 'returned'),
(15, 40, 23, 26, '2024-03-05 17:37:40', 'returned'),
(16, 41, 23, 40, '2024-03-05 17:37:41', 'returned'),
(20, 34, 19, 21, '2024-03-06 05:57:11', 'returned'),
(21, 35, 19, 49, '2024-03-06 05:57:12', 'returned'),
(22, 47, 22, 39, '2024-03-06 08:22:09', 'returned'),
(23, 38, 22, 43, '2024-03-06 08:22:28', 'returned'),
(24, 37, 22, 48, '2024-03-06 08:22:48', 'returned'),
(25, 36, 22, 17, '2024-03-06 08:22:53', 'returned'),
(26, 27, 22, 35, '2024-03-06 08:22:56', 'returned'),
(27, 26, 22, 34, '2024-03-06 08:22:58', 'returned'),
(28, 25, 22, 33, '2024-03-06 08:23:00', 'returned'),
(29, 54, 22, 21, '2024-03-06 08:24:28', 'returned'),
(30, 53, 22, 24, '2024-03-06 08:29:13', 'returned'),
(32, 52, 22, 17, '2024-03-06 08:31:23', 'returned'),
(33, 51, 22, 33, '2024-03-06 08:31:27', 'returned'),
(34, 62, 22, 35, '2024-03-06 08:32:56', 'returned'),
(35, 61, 22, 33, '2024-03-06 08:34:40', 'returned'),
(36, 60, 22, 34, '2024-03-06 08:35:47', 'returned'),
(37, 59, 22, 26, '2024-03-06 08:39:46', 'returned'),
(38, 58, 22, 24, '2024-03-06 08:40:31', 'returned'),
(39, 55, 22, 17, '2024-03-06 08:52:08', 'returned'),
(40, 67, 22, 49, '2024-03-06 09:03:40', 'returned'),
(41, 64, 22, 26, '2024-03-06 09:53:43', 'returned'),
(42, 20, 21, 44, '2024-03-06 10:01:56', 'returned'),
(43, 21, 21, 23, '2024-03-06 10:01:58', 'returned'),
(44, 22, 21, 22, '2024-03-06 10:02:00', 'returned'),
(46, 32, 21, 46, '2024-03-06 10:02:04', 'returned'),
(47, 44, 21, 42, '2024-03-06 10:02:07', 'returned'),
(48, 45, 21, 41, '2024-03-06 10:02:09', 'returned'),
(50, 18, 20, 45, '2024-03-06 10:02:41', 'returned'),
(51, 23, 20, 25, '2024-03-06 10:02:43', 'returned'),
(54, 43, 20, 36, '2024-03-06 10:02:48', 'returned'),
(56, 57, 22, 21, '2024-03-06 10:03:02', 'returned'),
(57, 63, 22, 24, '2024-03-06 10:03:04', 'returned'),
(58, 65, 22, 17, '2024-03-06 10:03:06', 'returned'),
(59, 66, 22, 35, '2024-03-06 10:03:08', 'returned'),
(61, 68, 22, 17, '2024-03-07 05:13:55', 'returned'),
(62, 82, 20, 34, '2024-03-07 11:30:00', 'returned'),
(63, 69, 19, 17, '2024-03-07 11:36:35', 'returned'),
(64, 78, 20, 22, '2024-03-07 11:51:47', 'returned'),
(65, 79, 20, 48, '2024-03-08 02:06:05', 'returned'),
(66, 77, 20, 21, '2024-03-08 05:44:57', 'returned'),
(67, 89, 20, 26, '2024-03-08 05:45:00', 'returned'),
(69, 80, 22, 41, '2024-03-08 05:45:14', 'returned'),
(70, 81, 22, 42, '2024-03-08 05:45:15', 'returned'),
(72, 83, 19, 23, '2024-03-08 05:45:29', 'returned'),
(73, 84, 19, 43, '2024-03-08 05:45:31', 'returned'),
(74, 85, 19, 44, '2024-03-08 05:45:32', 'returned'),
(76, 72, 21, 49, '2024-03-08 05:45:46', 'returned'),
(77, 73, 21, 40, '2024-03-08 05:45:48', 'returned'),
(78, 74, 21, 45, '2024-03-08 05:45:49', 'returned'),
(79, 75, 21, 46, '2024-03-08 05:45:51', 'returned'),
(80, 86, 23, 17, '2024-03-08 05:46:38', 'returned'),
(81, 87, 23, 24, '2024-03-08 05:46:40', 'returned'),
(82, 88, 23, 25, '2024-03-08 05:46:41', 'returned'),
(87, 99, 22, 23, '2024-03-10 12:30:37', 'returned'),
(89, 103, 21, 33, '2024-03-10 12:37:18', 'returned'),
(91, 90, 21, 17, '2024-03-10 12:37:36', 'returned'),
(92, 97, 20, 48, '2024-03-10 12:44:40', 'returned'),
(93, 101, 19, 24, '2024-03-10 13:06:22', 'returned'),
(94, 109, 21, 48, '2024-03-10 13:09:09', 'returned'),
(95, 106, 21, 17, '2024-03-10 13:09:11', 'returned'),
(100, 118, 22, 43, '2024-03-15 05:54:32', 'returned'),
(101, 117, 22, 24, '2024-03-15 05:54:35', 'returned'),
(102, 102, 19, 42, '2024-03-15 05:55:01', 'returned'),
(103, 92, 19, 46, '2024-03-15 05:55:03', 'returned'),
(104, 116, 20, 23, '2024-03-15 05:55:17', 'returned'),
(105, 115, 20, 41, '2024-03-15 05:55:19', 'returned'),
(106, 114, 20, 17, '2024-03-15 05:55:20', 'returned'),
(107, 96, 20, 22, '2024-03-15 05:55:22', 'returned'),
(108, 95, 20, 21, '2024-03-15 05:55:24', 'returned'),
(109, 112, 21, 39, '2024-03-15 05:55:42', 'returned'),
(110, 111, 21, 48, '2024-03-15 05:55:44', 'returned'),
(111, 110, 21, 45, '2024-03-15 05:55:46', 'returned'),
(114, 127, 22, 23, '2024-03-16 16:01:29', 'returned'),
(115, 135, 19, 24, '2024-03-17 02:57:12', 'returned'),
(116, 124, 19, 22, '2024-03-17 03:02:04', 'returned'),
(117, 137, 21, 39, '2024-03-17 03:05:04', 'returned'),
(118, 129, 21, 41, '2024-03-17 03:05:07', 'returned'),
(119, 128, 21, 23, '2024-03-17 03:05:09', 'returned'),
(120, 133, 20, 35, '2024-03-17 03:05:40', 'returned'),
(121, 132, 20, 33, '2024-03-17 03:05:41', 'returned'),
(122, 131, 20, 26, '2024-03-17 03:05:43', 'returned'),
(123, 123, 20, 21, '2024-03-17 03:05:45', 'returned'),
(124, 130, 23, 25, '2024-03-17 03:06:05', 'returned'),
(125, 126, 22, 79, '2024-03-17 03:06:21', 'returned'),
(126, 125, 22, 49, '2024-03-17 03:06:27', 'returned'),
(127, 122, 22, 76, '2024-03-17 03:06:29', 'returned'),
(128, 121, 22, 17, '2024-03-17 03:06:30', 'returned'),
(129, 136, 19, 48, '2024-03-17 03:06:54', 'returned'),
(130, 134, 19, 45, '2024-03-17 03:06:56', 'returned'),
(131, 142, 19, 76, '2024-03-17 11:17:48', 'returned'),
(132, 139, 19, 21, '2024-03-17 11:19:14', 'returned'),
(133, 141, 19, 79, '2024-03-17 11:23:28', 'returned'),
(134, 145, 19, 79, '2024-03-17 11:26:10', 'returned'),
(135, 144, 19, 76, '2024-03-17 11:26:29', 'returned'),
(136, 146, 19, 81, '2024-03-17 11:27:37', 'returned'),
(137, 138, 19, 17, '2024-03-17 11:27:58', 'returned'),
(138, 147, 19, 79, '2024-03-17 11:30:24', 'returned'),
(139, 148, 19, 81, '2024-03-17 11:32:01', 'returned'),
(140, 149, 19, 81, '2024-03-17 11:32:42', 'returned'),
(141, 150, 19, 76, '2024-03-17 11:34:11', 'returned'),
(142, 151, 19, 79, '2024-03-17 11:36:15', 'returned'),
(143, 153, 19, 79, '2024-03-17 11:41:08', 'returned'),
(144, 155, 21, 17, '2024-03-17 11:58:24', 'returned'),
(145, 154, 21, 49, '2024-03-17 11:59:24', 'returned'),
(146, 143, 20, 23, '2024-03-17 11:59:40', 'returned'),
(147, 140, 20, 80, '2024-03-17 11:59:46', 'returned'),
(148, 152, 19, 81, '2024-03-17 12:00:13', 'returned'),
(149, 156, 19, 81, '2024-03-17 14:38:34', 'returned'),
(150, 161, 19, 79, '2024-03-17 15:42:32', 'returned'),
(151, 162, 20, 49, '2024-03-17 16:59:38', 'returned'),
(152, 164, 20, 76, '2024-03-18 01:34:43', 'returned'),
(153, 167, 20, 49, '2024-03-18 06:31:41', 'returned'),
(154, 163, 19, 79, '2024-03-18 07:58:38', 'returned'),
(155, 169, 20, 22, '2024-03-18 07:58:57', 'returned'),
(156, 166, 20, 23, '2024-03-18 07:59:03', 'returned'),
(157, 165, 20, 76, '2024-03-18 07:59:07', 'returned'),
(158, 168, 21, 49, '2024-03-18 07:59:24', 'returned'),
(159, 160, 23, 21, '2024-03-18 07:59:41', 'returned'),
(160, 159, 23, 17, '2024-03-18 07:59:46', 'returned'),
(161, 158, 23, 81, '2024-03-18 07:59:50', 'returned'),
(162, 157, 23, 80, '2024-03-18 07:59:57', 'returned'),
(164, 172, 20, 91, '2024-03-19 04:15:35', 'returned'),
(165, 179, 26, 94, '2024-03-19 07:31:28', 'returned'),
(166, 186, 23, 76, '2024-03-19 17:39:45', 'returned'),
(167, 187, 23, 23, '2024-03-19 17:39:52', 'returned'),
(168, 185, 20, 35, '2024-03-19 17:52:50', 'returned'),
(169, 183, 20, 97, '2024-03-19 17:53:02', 'returned'),
(170, 171, 20, 87, '2024-03-19 17:53:10', 'returned'),
(171, 173, 20, 92, '2024-03-19 17:53:27', 'returned'),
(174, 174, 21, 91, '2024-03-19 17:56:33', 'returned'),
(176, 178, 19, 95, '2024-03-19 17:59:04', 'returned'),
(177, 177, 19, 24, '2024-03-19 17:59:10', 'returned'),
(178, 176, 19, 41, '2024-03-19 17:59:16', 'returned'),
(179, 188, 23, 21, '2024-03-19 17:59:52', 'returned'),
(181, 196, 21, 90, '2024-03-20 14:46:20', 'returned'),
(182, 195, 19, 97, '2024-03-20 14:47:38', 'returned'),
(183, 194, 19, 95, '2024-03-20 14:47:45', 'returned'),
(184, 193, 19, 89, '2024-03-20 14:47:53', 'returned'),
(185, 182, 26, 36, '2024-03-20 14:48:17', 'returned'),
(186, 181, 26, 22, '2024-03-20 14:48:23', 'returned'),
(187, 180, 26, 17, '2024-03-20 14:48:29', 'returned'),
(188, 192, 20, 21, '2024-03-20 14:48:58', 'returned'),
(189, 191, 20, 98, '2024-03-20 14:49:06', 'returned'),
(190, 190, 23, 24, '2024-03-20 14:49:26', 'returned');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `occupation` varchar(100) NOT NULL,
  `contact_num` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `user_type` varchar(255) NOT NULL,
  `image` blob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `full_name`, `occupation`, `contact_num`, `address`, `password`, `created_at`, `user_type`, `image`) VALUES
(14, 'admin', '', '', '', '', '', '$2y$10$hx7IQKH9AedGbB9t7NASMeAEEdwUM3auxfpFUm5w5l1epjKmH5pqa', '2024-02-19 00:01:30', 'admin', 0x496d616765732f61646d696e20696d6167652e6a7067),
(19, 'user01', '', '', '', '0', '', '$2y$10$ZcIJAaijt7fsOM94WOQ7VO9iuOlngdHeeSmbdt7zPbiLIjlopukOS', '2024-02-29 17:32:35', 'user', 0x496d616765732f757365722069636f6e20332e706e67),
(20, 'arjec', 'arjecdizon99@gmail.com', 'Arjec Jose Dizon', 'Student', '+639158423449', 'Guiwan, Zamboanga city', '$2y$10$UaJqQA3MwCIwPAVoET82iuT4L/bXTvpO20GqnlqjYodqh3LlQzc96', '2024-03-04 23:12:13', 'user', 0x496d616765732f73747564656e742069636f6e2e706e67),
(21, 'alim', '', 'Al-khazri Sali Alim', '', '', '', '$2y$10$hJTfIblmbU9nWO4wWhkiS.TW.oJtns.Y5M.imeF7BlSmDamIqsfgG', '2024-03-05 03:36:45', 'user', 0x496d616765732f73747564656e742069636f6e20322e706e67),
(22, 'luffy', '', '', '', '0', '', '$2y$10$Lo71Gy6hdn5vmXgzhqzXo.v4vLfVS6PsKV/BVGA.MSlNmx1Jv2PV2', '2024-03-05 03:55:05', 'user', 0x496d616765732f6c756666792e6a7067),
(23, 'johndoe69', '', '', '', '0', '', '$2y$10$FWOVzitIKMYjAgTiN9kqZO.rdFLddWFPYRHNUxj8UvjGUxGl/JHha', '2024-03-06 00:43:33', 'user', 0x496d616765732f757365722069636f6e20342e706e67),
(26, 'user02', '', '', '', '', '', '$2y$10$bSwzGRlOUB.9LHBVhRlSDOP9hubnK8mlOnjgK3odwzWIeGO2QyYby', '2024-03-19 13:08:31', 'user', 0x496d616765732f757365722069636f6e20332e706e67);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`);

--
-- Indexes for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD PRIMARY KEY (`borrow_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `return_history`
--
ALTER TABLE `return_history`
  ADD PRIMARY KEY (`return_id`),
  ADD KEY `borrow_id` (`borrow_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=102;

--
-- AUTO_INCREMENT for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=222;

--
-- AUTO_INCREMENT for table `return_history`
--
ALTER TABLE `return_history`
  MODIFY `return_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=191;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD CONSTRAINT `borrowed_books_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `borrowed_books_ibfk_2` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);

--
-- Constraints for table `return_history`
--
ALTER TABLE `return_history`
  ADD CONSTRAINT `return_history_ibfk_1` FOREIGN KEY (`borrow_id`) REFERENCES `borrowed_books` (`borrow_id`),
  ADD CONSTRAINT `return_history_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `return_history_ibfk_3` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
