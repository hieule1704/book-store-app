-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 26, 2025 at 09:21 AM
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
-- Database: `shop_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `author`
--

CREATE TABLE `author` (
  `id` int(11) NOT NULL,
  `author_name` varchar(100) NOT NULL,
  `profile_picture` varchar(255) DEFAULT 'No-profile-picture.jpeg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `author`
--

INSERT INTO `author` (`id`, `author_name`, `profile_picture`) VALUES
(1, 'Mark Manson', 'Mark-Manson.jpg'),
(2, 'Mel Robbins', 'Mel-Robbins.jpg'),
(3, 'Joseph Nguyen', 'Joseph-Nguyen.jpg'),
(4, 'James Clear', 'James-Clear.jpeg'),
(5, 'Jordan Peterson', 'Jordan-Peterson.jpeg'),
(6, 'Garry Keller', 'Garry Keller.jpg'),
(7, 'Tim Vũ', 'Tim-Vu.jpg'),
(8, 'Jim Rohn', 'Jim-Rohn.jpeg'),
(9, 'Darren Hardy', 'Darren Hardy.jpg'),
(10, 'Emily Henry ', 'No-profile-picture.jpeg'),
(11, 'Carley Fortune', 'No-profile-picture.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `blogs`
--

CREATE TABLE `blogs` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `summary` varchar(255) DEFAULT NULL,
  `image` varchar(255) DEFAULT 'other_resource/no-picture-book.jpg',
  `author_id` int(11) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `views` int(11) DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `content`, `summary`, `image`, `author_id`, `category_id`, `views`, `created_at`, `updated_at`) VALUES
(1, 'The Power of Reading Every Day', '<p>In our fast-paced digital world, it\'s easy to overlook the simple joys and profound benefits of reading. Yet, setting aside time to read every day—even just a few pages—can transform your mind, your habits, and your life. Whether you\'re flipping through a novel, diving into a non-fiction book, or exploring articles on topics you love, the habit of daily reading is a powerful investment in your personal growth. 1. Mental Stimulation and Cognitive Growth Just like physical exercise strengthens your body, reading stimulates your brain. Studies have shown that reading can help slow down the progression of cognitive decline and improve brain connectivity. Every time you read, your brain creates new pathways and strengthens existing ones, which enhances memory, comprehension, and problem-solving skills. 2. Expanding Knowledge and Vocabulary Every book you open has something to teach you. Whether it’s learning a new concept, understanding a different culture, or simply picking up new words, reading is one of the best ways to expand your knowledge. A broader vocabulary can improve your writing and communication skills, boost your confidence, and even open new doors professionally. 3. Reducing Stress and Enhancing Focus Reading provides a healthy escape from the chaos of daily life. When you lose yourself in a good story, your stress levels decrease, and your mind finds a sense of calm. Unlike scrolling on social media, reading helps you stay focused for longer periods, improving your attention span and concentration over time. 4. Inspiring Creativity and Imagination Books are portals to other worlds. They allow you to explore new perspectives, imagine different possibilities, and spark ideas that may never have surfaced otherwise. Fiction enhances empathy and emotional intelligence, while non-fiction inspires action and deep thinking. The more you read, the more your creativity flourishes. 5. Building Discipline and Consistency Cultivating the habit of daily reading requires commitment and discipline—two qualities that spill over into other areas of life. Just 10 to 20 minutes a day can create a rhythm of consistency that boosts your sense of purpose and personal achievement. 6. Personal and Professional Growth Successful people across industries often share a common trait: they are avid readers. From CEOs to thought leaders, reading is part of their routine because it keeps them informed, inspired, and ahead of the curve. Whether you\'re reading to improve your skills, gain insight, or simply recharge, the returns are immeasurable. Final Thoughts Reading every day isn’t just a hobby—it’s a life-changing practice. In a world full of distractions, choosing to read is a radical act of self-care and empowerment. So grab a book, find a quiet corner, and let the journey begin. One page at a time, you’re becoming a better version of yourself.</p>', NULL, 'IMG_1639.JPG', 1, 5, 7, '2025-05-18 12:14:31', '2025-11-26 14:20:10'),
(2, 'Top 5 Must-Read Books of 2025', '<p>Top 5 Must-Read Books of 2025 Each year brings a fresh wave of literary treasures—and 2025 is already proving to be a standout. From thrilling fiction to thought-provoking non-fiction, this year’s best books are full of powerful storytelling, timely insights, and unforgettable characters. Whether you\'re a lifelong reader or just getting back into the habit, these five titles deserve a spot on your bookshelf. 1. “The Memory Architects” by Celeste Ng Genre: Literary Fiction Why Read It: Bestselling author Celeste Ng returns with a haunting, beautifully written novel about identity, memory, and the cost of technology. Set in a near-future society where memories can be modified or erased, the story follows a mother and daughter caught between truth and comfort. Ng’s lyrical prose and emotional depth make this one of the year’s most talked-about books. 2. “Invisible Lines” by Malcolm Gladwell Genre: Non-Fiction / Psychology Why Read It: Gladwell dives into the unseen forces that govern human behavior—biases, perceptions, and the mental shortcuts we don’t even realize we’re using. “Invisible Lines” combines real-life stories with research from psychology, sociology, and neuroscience to explore how we can better understand each other in a divided world. 3. “The Fourth Horizon” by N.K. Jemisin Genre: Science Fiction / Fantasy Why Read It: Hugo Award-winner N.K. Jemisin returns with the first installment in a bold new series that blends futuristic world-building with ancient mythology. With deeply layered characters, political intrigue, and a richly imagined universe, The Fourth Horizon is a must-read for fans of speculative fiction and epic storytelling. 4. “Flourish: Designing a Life with Purpose” by Marie Forleo Genre: Personal Development Why Read It: In her latest book, Marie Forleo shares a practical guide to building a life that aligns with your values, passions, and goals. With a mix of motivational insight and actionable advice, Flourish empowers readers to take ownership of their choices and design a life they love—one step at a time. 5. “This Is How We Heal” by Dr. Rhea Santos Genre: Wellness / Mental Health Why Read It: Blending scientific research with compassionate storytelling, Dr. Santos offers a powerful roadmap for emotional healing in a post-pandemic world. This Is How We Heal tackles anxiety, burnout, and trauma with tools for self-compassion, mindfulness, and community connection. Final Thoughts Whether you\'re looking to escape into an imaginative world, sharpen your mind, or reconnect with yourself, these five must-read books of 2025 offer something for every reader. So grab a cup of coffee, find a cozy corner, and let these powerful stories and ideas inspire your year ahead.</p>', NULL, '2yqty956js151.jpg', 4, 2, 1, '2025-05-18 12:16:28', '2025-11-26 09:34:45'),
(3, 'How to Start a Book Club', '<p>How to Start a Book Club: A Step-by-Step Guide Do you love books and crave meaningful conversations with others who feel the same? Starting a book club might be one of the most rewarding ways to combine your love for reading with connection and community. Whether you\'re building a club with friends, coworkers, classmates, or online readers, this guide will help you launch and sustain a successful book club. 1. Define the Purpose of Your Club Before inviting members or picking books, decide on your book club’s purpose: Is it purely social or more academic? Will you focus on fiction, non-fiction, self-development, or a mix? Are you reading for fun, deep discussion, or personal growth? Clear goals will shape everything from the tone of meetings to your book choices. 2. Choose the Right Members Think about the size and dynamic of your group. A good number is 5–12 people—small enough for everyone to speak, but large enough to keep discussions lively. You can invite: Friends or family Colleagues or classmates Members of a community group or online audience Tip: Look for people who are reliable, curious, and respectful of others\' opinions. 3. Decide on the Format and Meeting Style Will your club meet in person, online, or a hybrid? Consider: In-person: Great for bonding; ideal for neighborhoods or campuses. Online (Zoom, Google Meet, etc.): Perfect for long-distance members or busy schedules. Asynchronous (Discord, Facebook groups): Flexible, but may lack real-time engagement. Also, choose how often you\'ll meet—monthly is a common and manageable rhythm. 4. Pick Your First Book Start with a book that’s relatively short, engaging, and discussion-friendly. You could: Vote on a few suggestions from members Rotate who chooses each month Follow popular reading lists or book prize nominees Tip: Consider books with discussion questions at the end or search online for guides to help you lead thoughtful conversations. 5. Plan and Structure Your Meetings A great book club meeting is more than just chatting about the plot. Structure can help: Start with a welcome or quick personal catch-up Share first impressions or favorite parts Dive into themes, characters, or big ideas Use questions to guide deeper discussion End with next month’s book and housekeeping Keep things friendly, respectful, and open-ended. Everyone should feel heard and included. 6. Make It Fun and Sustainable To keep the momentum going: Switch up genres to keep things fresh Try themed meetings (bring food related to the book, dress up, etc.) Host occasional game nights or watch book-to-film adaptations Share highlights or quotes in a group chat Flexibility and enthusiasm are key to long-term success. Final Thoughts Starting a book club isn’t just about reading—it’s about building a space for connection, curiosity, and conversation. With the right people and a little planning, your book club can become a joyful, inspiring part of your routine. So gather your favorite people, pick your first book, and let the reading adventures begin!</p>', NULL, '2e9d1-sheryl-sandberg-quote.png', 7, 1, 2, '2025-05-18 12:17:13', '2025-11-26 09:38:19'),
(4, 'Atomic Habits by James Clear – A Life-Changing Guide to Building Better Habits', '<p><strong>Introduction:</strong><br><br><i>In today’s fast-paced world, forming good habits can be the key to long-term success. “Atomic Habits” by James Clear is a powerful book that offers practical strategies for improving our daily lives through small, consistent changes.</i></p><blockquote><p><strong>Summary:</strong><br>The book explores how tiny changes, or \"atomic habits,\" can lead to remarkable results. It’s based on the idea that we don’t rise to the level of our goals—we fall to the level of our systems. Clear introduces the Four Laws of Behavior Change: Make it obvious, Make it attractive, Make it easy, and Make it satisfying.</p></blockquote><p><strong>Key Takeaways:</strong></p><p>Small changes compound over time.</p><p>Focus on identity change, not just behavior.</p><p>Design your environment to support your goals.</p><p>Habit tracking can boost motivation.</p><p><strong>Final Thoughts:</strong><br>“Atomic Habits” is more than just a self-help book. It’s a blueprint for transformation. Whether you\'re trying to read more, eat healthier, or become more productive, this book provides actionable tools. Highly recommended for anyone interested in personal growth.</p>', NULL, 'Atomic Habits.jpg', 4, 1, 6, '2025-05-19 14:44:32', '2025-11-26 14:33:30');

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `price` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Technology'),
(2, 'Self-Help'),
(3, 'Fiction'),
(4, 'Writing Tips'),
(5, 'Lifestyle');

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `message` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `email`, `number`, `message`) VALUES
(2, 12, 'Lê Chí Hiếu', 'lehieu17042004@gmail.com', '0172528523', 'Bạn thật đẹp trai'),
(3, 12, 'Lê Chí Hiếu', 'lchieu1704@gmail.com', '0868941055', 'Test chức năng'),
(4, 12, 'Lê Chí Hiếu', 'ethlee1704@gmail.com', '0868941055', '086894105508689410550868941055'),
(5, 13, 'Lê Chí Hiếu', 'ethlee1704@gmail.com', '0868941055', 'Web cuồi quá'),
(6, 14, 'Lê Chí Hiếu', 'hieu_dth225642@student.agu.edu.vn', '0868941055', 'Test');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(12) NOT NULL,
  `email` varchar(100) NOT NULL,
  `method` varchar(50) NOT NULL,
  `address` varchar(500) NOT NULL,
  `total_products` varchar(1000) NOT NULL,
  `total_price` int(11) NOT NULL,
  `placed_on` varchar(50) NOT NULL,
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `name`, `number`, `email`, `method`, `address`, `total_products`, `total_price`, `placed_on`, `payment_status`) VALUES
(8, 12, 'Lê Chí Hiếu', '0172528523', 'lehieu17042004@gmail.com', 'cash on delivery', 'Flat no. 3, linkedin.com/in/chi-hieu-le-8b1040297, Long Xuyen, Vietnam - 90000', 'Boundaries = Freedom (1), Atomic Habits (1)', 33, '26-Nov-2025', 'completed'),
(9, 12, 'Lê Chí Hiếu', '0172528523', 'lehieu17042004@gmail.com', 'cash on delivery', 'Flat no. 3, linkedin.com/in/chi-hieu-le-8b1040297, Long Xuyen, Vietnam - 90000', 'The Let Them Theory (24)', 360, '26-Nov-2025', 'completed'),
(10, 12, 'Lê Chí Hiếu', '0172528523', 'lehieu17042004@gmail.com', 'credit card', 'Flat no. 3, linkedin.com/in/chi-hieu-le-8b1040297, Long Xuyen, Vietnam - 90000', 'The Let Them Theory (1)', 15, '26-Nov-2025', 'completed'),
(11, 12, 'Lê Chí Hiếu', '0172528523', 'lehieu17042004@gmail.com', 'cash on delivery', 'Flat no. 3, linkedin.com/in/chi-hieu-le-8b1040297, Long Xuyen, Vietnam - 90000', 'The Let Them Theory (1)', 15, '26-Nov-2025', 'pending'),
(12, 12, 'Lê Chí Hiếu', '0172528523', 'lehieu17042004@gmail.com', 'cash on delivery', 'Flat no. 3, linkedin.com/in/chi-hieu-le-8b1040297, Long Xuyen, Vietnam - 90000', 'Models: Attract Women Through Honesty (1)', 13, '26-Nov-2025', 'pending'),
(13, 12, 'Lê Chí Hiếu', '0868941055', 'lchieu1704@gmail.com', 'cash on delivery', 'Flat no. 123, Vo Thi Sau, Long Xuyen, Vietnam - 90000', 'The Day That Turns Your Life Around (1)', 14, '26-Nov-2025', 'completed'),
(14, 12, 'Lê Chí Hiếu', '0868941055', 'lchieu1704@gmail.com', 'qr code', 'Flat no. 123, Vo Thi Sau, Long Xuyen, Vietnam - 90000', 'The Day That Turns Your Life Around (1)', 14, '26-Nov-2025', 'completed'),
(17, 13, 'Lê Chí Hiếu', '0868941055', 'lchieu1704@gmail.com', 'qr code', 'Flat no. 123, Vo Thi Sau, Long Xuyen, Vietnam - 90000', 'The Let Them Theory (1)', 15, '26-Nov-2025', 'pending'),
(18, 13, 'Lê Chí Hiếu', '0868941055', 'lchieu1704@gmail.com', 'cash on delivery', 'Flat no. 123, Vo Thi Sau, Long Xuyen, Vietnam - 90000', 'The Let Them Theory (1)', 15, '26-Nov-2025', 'pending'),
(21, 13, 'Lê Chí Hiếu', '0868941055', 'lchieu1704@gmail.com', 'qr code', 'Flat no. 123, Vo Thi Sau, Long Xuyen, Vietnam - 90000', 'The Day That Turns Your Life Around (1), The Let Them Theory (3)', 59, '26-Nov-2025', 'completed'),
(22, 14, 'Lê Chí Hiếu', '0868941055', 'hieu_dth225642@student.agu.edu.vn', 'cash on delivery', 'Flat no. 123, Vo Thi Sau, Long Xuyen, Vietnam - 90000', 'The 5 Second Rule (1), The Day That Turns Your Life Around (1), The Power of Ambition (3)', 77, '26-Nov-2025', 'pending'),
(23, 14, 'Lê Chí Hiếu', '0868941055', 'hieu_dth225642@student.agu.edu.vn', 'qr code', 'Flat no. 123, Vo Thi Sau, Long Xuyen, Vietnam - 90000', 'The Let Them Theory (1)', 15, '26-Nov-2025', 'pending'),
(24, 14, 'Lê Chí Hiếu', '0868941055', 'hieu_dth225642@student.agu.edu.vn', 'bank transfer', 'Flat no. 123, Vo Thi Sau, Long Xuyen, Vietnam - 90000', 'The Day That Turns Your Life Around (1)', 14, '26-Nov-2025', 'completed');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
(2, 8, 13, 1, 13),
(3, 8, 2, 1, 20),
(4, 9, 19, 24, 15),
(5, 10, 19, 1, 15),
(6, 11, 19, 1, 15),
(7, 12, 14, 1, 13),
(8, 13, 18, 1, 14),
(9, 14, 18, 1, 14),
(12, 17, 19, 1, 15),
(13, 18, 19, 1, 15),
(16, 21, 18, 1, 14),
(17, 21, 19, 3, 15),
(18, 22, 16, 1, 18),
(19, 22, 18, 1, 14),
(20, 22, 20, 3, 15),
(21, 23, 19, 1, 15),
(22, 24, 18, 1, 14);

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `book_name` varchar(100) NOT NULL,
  `author_id` int(11) NOT NULL,
  `publisher_id` int(11) NOT NULL,
  `book_description` text DEFAULT NULL,
  `tag` varchar(100) DEFAULT NULL,
  `publish_year` int(11) DEFAULT NULL,
  `total_page` int(11) DEFAULT NULL,
  `price` int(11) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `image` varchar(100) NOT NULL DEFAULT 'no-picture-book.jpg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `book_name`, `author_id`, `publisher_id`, `book_description`, `tag`, `publish_year`, `total_page`, `price`, `stock_quantity`, `image`) VALUES
(1, 'The Subtle Art of Not Giving a F*ck', 1, 2, 'A counterintuitive approach to living a good life.', 'bestseller', 2016, 224, 15, 5, 'The Subtle Art of Not Giving a Fuck.jpg'),
(2, 'Atomic Habits', 4, 4, 'An Easy & Proven Way to Build Good Habits & Break Bad Ones.', 'bestseller', 2018, 320, 20, 2, 'Atomic Habits.jpg'),
(3, 'The ONE Thing', 6, 1, 'The surprisingly simple truth behind extraordinary results.', 'bestseller', 2013, 240, 17, 11, 'The ONE Thing.jpg'),
(4, '12 Rules for Life', 5, 1, 'An Antidote to Chaos.', 'sales', 2018, 409, 30, 64, '12 Rules for Life.jpg'),
(5, 'Great Big Beautiful Life', 10, 1, 'Cuốn sách truyền cảm hứng từ Emily Henry về việc sống một cuộc đời rực rỡ, đậm sắc màu cảm xúc và sự tự do nội tâm.', 'new release', 2020, 250, 18, 100, '5c0c42743210874ede011.jpg'),
(6, 'One Golden Summer', 11, 3, 'A summer filled with light and unforgettable memories. Carley Fortune tells a story of love and life-changing decisions.', 'new release', 2022, 320, 20, 58, 'One golden Summer.jpg'),
(7, 'Warriors', 8, 2, 'A fantasy story about warrior cats, their survival, loyalty, and honor.', '', 2003, 300, 16, 138, 'Warriors.jpg'),
(8, 'The Wager', 10, 1, 'David Grann recounts a remarkable survival journey at sea, betrayal, and extraordinary willpower.', 'new release', 2023, 288, 31, 68, 'The wager.jpg'),
(9, 'Don’t Believe Everything You Think', 3, 2, 'Joseph Nguyen explains how the mind creates suffering and how to live more peacefully by not believing every thought.', 'bestseller', 2022, 160, 12, 9, 'Dont belie everything you think.jpg'),
(10, 'Beyond Thoughts', 3, 2, 'A deep look into presence and how to transcend negative thinking for inner peace.', 'bestseller', 2020, 170, 14, 7, 'Beyond thoughts.jpg'),
(11, 'Bí quyết thành công ở đại học', 7, 6, 'Tim Vũ shares practical strategies for effective learning and building a career from the university years.', '', 2019, 200, 10, 76, 'Bí quyết thành công ở đại học.jpg'),
(12, 'The Overthinker’s Guide to Making Decisions', 3, 4, 'A guide for overthinkers on simplifying decisions, reducing anxiety, and taking effective action.', 'new release', 2023, 160, 13, 128, 'The overthinker guide to making decision.jpg'),
(13, 'Boundaries = Freedom', 3, 4, 'Setting boundaries is the first step to personal freedom, life control, and healthier relationships.', 'bestseller', 2020, 160, 13, 7, 'Boundaries equal freedom.jpg'),
(14, 'Models: Attract Women Through Honesty', 1, 1, 'Mark Manson explores how to become more attractive through honesty, self-improvement, and real value.', 'bestseller', 2020, 280, 13, 13, 'Models Attract Women Through Honesty.jpg'),
(15, 'Take Charge of Your Life: Unlocking Influence, Wealth and Potential', 8, 5, 'Jim Rohn shares timeless principles for mastering life, cultivating positive thinking, and achieving personal success.', 'classic', 1985, 150, 16, 60, 'Take Charge of Your Life Unlocking Influence, Wealth, and Power.jpg'),
(16, 'The 5 Second Rule', 2, 2, 'Mel Robbins presents a simple method to take action, overcome procrastination, and build confidence in 5 seconds.', 'bestseller', 2017, 240, 18, 7, 'The 5 second rule.jpg'),
(17, 'The Compound Effect', 9, 5, 'Darren Hardy emphasizes how small consistent actions lead to extraordinary success over time.', 'bestseller', 2017, 240, 18, 11, 'The Compound Effect.jpg'),
(18, 'The Day That Turns Your Life Around', 8, 3, 'Jim Rohn shares stories about turning points in life and how we can create our own breakthroughs.', 'classic', 1993, 130, 14, 64, 'The Day that Turns Your Life Around.jpg'),
(19, 'The Let Them Theory', 2, 1, 'Mel Robbins introduces the “Let Them” theory to reduce stress and live according to your own values.', 'new release', 2023, 180, 15, 79, 'The let them theory.jpg'),
(20, 'The Power of Ambition', 8, 1, 'Jim Rohn inspires readers to pursue ambition, set goals, and stay committed to success.', '', 1994, 140, 15, 111, 'The Power of Ambition.jpg'),
(29, 'The Psychology of Money', 1, 2, NULL, NULL, NULL, NULL, 18, 50, 'money.jpg'),
(30, 'Deep Work', 4, 1, NULL, NULL, NULL, NULL, 22, 30, 'deepwork.jpg'),
(31, 'Rich Dad Poor Dad', 8, 5, NULL, NULL, NULL, NULL, 15, 100, 'richdad.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `publisher`
--

CREATE TABLE `publisher` (
  `id` int(11) NOT NULL,
  `publisher_name` varchar(100) NOT NULL,
  `profile_image` varchar(255) DEFAULT 'No-profile-picture.jpeg'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `publisher`
--

INSERT INTO `publisher` (`id`, `publisher_name`, `profile_image`) VALUES
(1, 'Jaico', 'Jaico-publisher.jpeg'),
(2, 'Penguin', 'Penguin-publisher.jpeg'),
(3, 'Puffin', 'Puffin-publisher.jpeg'),
(4, 'Bloomsbury', 'Bloomsbury-publisher.jpeg'),
(5, 'HarperOne', 'HarperOne-publisher.jpeg'),
(6, 'Nhã Nam', 'Nhã Nam-publisher.jpeg');

-- --------------------------------------------------------

--
-- Table structure for table `subscribers`
--

CREATE TABLE `subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subscribed_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscribers`
--

INSERT INTO `subscribers` (`id`, `email`, `subscribed_at`) VALUES
(1, 'lchieu1704@gmail.com', '2025-11-26 08:25:23');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `verification_code` varchar(255) DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `remember_token` varchar(128) DEFAULT NULL,
  `user_type` varchar(20) NOT NULL DEFAULT 'user',
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `verification_code`, `is_verified`, `remember_token`, `user_type`, `reset_token`, `reset_expiry`) VALUES
(1, 'Lê Chí Hiếu', 'hieu123@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, 1, NULL, 'admin', NULL, NULL),
(3, 'Hiếu LC', 'lehieu17042004@gmail.com', '', NULL, 0, NULL, 'user', NULL, NULL),
(4, 'CHAT BOT GPT 1', 'gpt1704.1@gmail.com', '', NULL, 0, NULL, 'user', NULL, NULL),
(12, 'Hieu Simp', 'lchieu1704@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', NULL, 1, NULL, 'user', NULL, NULL),
(13, 'Customer 1', 'ethlee1704@gmail.com', 'e10adc3949ba59abbe56e057f20f883e', NULL, 1, NULL, 'user', NULL, NULL),
(14, 'heiu', 'hieu_dth225642@student.agu.edu.vn', 'e10adc3949ba59abbe56e057f20f883e', NULL, 1, NULL, 'user', '15e9a869c6966e7551aa5f420ea191f8', '2025-11-26 09:13:41');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `author`
--
ALTER TABLE `author`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `author_id` (`author_id`),
  ADD KEY `publisher_id` (`publisher_id`);

--
-- Indexes for table `publisher`
--
ALTER TABLE `publisher`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `subscribers`
--
ALTER TABLE `subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `author`
--
ALTER TABLE `author`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `publisher`
--
ALTER TABLE `publisher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subscribers`
--
ALTER TABLE `subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `blogs`
--
ALTER TABLE `blogs`
  ADD CONSTRAINT `blogs_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`);

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`);

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `author` (`id`),
  ADD CONSTRAINT `products_ibfk_2` FOREIGN KEY (`publisher_id`) REFERENCES `publisher` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
