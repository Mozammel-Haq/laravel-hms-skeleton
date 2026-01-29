import React from 'react';
import { Calendar, User, ArrowRight, Tag } from 'lucide-react';
import { Link } from 'react-router-dom';
import Button from '../../components/common/Button';

const Blog = () => {
  const posts = [
    {
      id: 1,
      title: "Understanding Heart Health: Tips for a Healthy Life",
      excerpt: "Learn about the importance of cardiovascular health and simple lifestyle changes that can make a big difference.",
      author: "Dr. Sarah Johnson",
      date: "Oct 15, 2025",
      category: "Cardiology",
      image: "https://images.unsplash.com/photo-1571019614242-c5c5dee9f50b?auto=format&fit=crop&q=80&w=800",
      readTime: "5 min read"
    },
    {
      id: 2,
      title: "The Importance of Regular Check-ups",
      excerpt: "Why preventive care is your best defense against serious health issues and what to expect during your annual visit.",
      author: "Dr. Michael Chen",
      date: "Oct 10, 2025",
      category: "Wellness",
      image: "https://images.unsplash.com/photo-1576091160399-112ba8d25d1d?auto=format&fit=crop&q=80&w=800",
      readTime: "4 min read"
    },
    {
      id: 3,
      title: "Managing Stress in the Modern World",
      excerpt: "Effective strategies for mental wellness and stress management in today's fast-paced environment.",
      author: "Dr. Emily Wilson",
      date: "Oct 05, 2025",
      category: "Mental Health",
      image: "https://images.unsplash.com/photo-1544367563-12123d8965cd?auto=format&fit=crop&q=80&w=800",
      readTime: "6 min read"
    },
    {
      id: 4,
      title: "Nutrition Myths Debunked",
      excerpt: "Separating fact from fiction when it comes to diet trends and nutritional advice.",
      author: "Jessica Brown, RD",
      date: "Sep 28, 2025",
      category: "Nutrition",
      image: "https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&q=80&w=800",
      readTime: "7 min read"
    },
    {
      id: 5,
      title: "Advances in Pediatric Care",
      excerpt: "New technologies and treatments that are improving health outcomes for children.",
      author: "Dr. Robert Taylor",
      date: "Sep 20, 2025",
      category: "Pediatrics",
      image: "https://images.unsplash.com/photo-1516574187841-693083f04968?auto=format&fit=crop&q=80&w=800",
      readTime: "5 min read"
    },
    {
      id: 6,
      title: "Exercise for Joint Health",
      excerpt: "Low-impact exercises that can help maintain joint mobility and reduce pain.",
      author: "Dr. Lisa Anderson",
      date: "Sep 15, 2025",
      category: "Orthopedics",
      image: "https://images.unsplash.com/photo-1571019613454-1cb2f99b2d8b?auto=format&fit=crop&q=80&w=800",
      readTime: "4 min read"
    }
  ];

  return (
    <div className="pt-24 pb-20 bg-secondary-50 dark:bg-secondary-950 min-h-screen transition-colors duration-300">
      <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        {/* Header */}
        <div className="text-center max-w-3xl mx-auto mb-16 animate-fade-in-up">
          <span className="text-primary-600 dark:text-primary-400 font-bold tracking-wider uppercase text-sm">Health Blog</span>
          <h1 className="text-4xl font-bold text-secondary-900 dark:text-white mt-2 mb-4">Latest News & Insights</h1>
          <p className="text-secondary-600 dark:text-secondary-400 text-lg">
            Expert medical advice, health tips, and hospital news to keep you informed.
          </p>
        </div>

        {/* Featured Post (First one) */}
        <div className="mb-16 animate-fade-in">
          <div className="bg-white dark:bg-secondary-900 rounded-2xl overflow-hidden border border-secondary-200 dark:border-secondary-800 grid md:grid-cols-2">
            <div className="h-64 md:h-auto overflow-hidden relative group">
              <div className="absolute inset-0 bg-secondary-900/10 dark:bg-secondary-950/20 group-hover:bg-transparent transition-colors z-10"></div>
              <img
                src={posts[0].image}
                alt={posts[0].title}
                className="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700"
              />
            </div>
            <div className="p-8 md:p-12 flex flex-col justify-center">
              <div className="flex items-center gap-3 text-sm text-primary-600 dark:text-primary-400 font-medium mb-3">
                <span className="px-3 py-1 rounded-full bg-primary-100 dark:bg-primary-900/30 border border-primary-200 dark:border-primary-700/30">{posts[0].category}</span>
                <span>{posts[0].readTime}</span>
              </div>
              <h2 className="text-2xl md:text-3xl font-bold text-secondary-900 dark:text-white mb-4 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                <Link to="/blog">{posts[0].title}</Link>
              </h2>
              <p className="text-secondary-600 dark:text-secondary-400 mb-6 text-lg">
                {posts[0].excerpt}
              </p>
              <div className="flex items-center justify-between mt-auto">
                <div className="flex items-center gap-3">
                  <div className="w-10 h-10 rounded-full bg-secondary-200 dark:bg-secondary-800 flex items-center justify-center text-secondary-500 dark:text-secondary-400 font-bold">
                    {posts[0].author.charAt(0)}
                  </div>
                  <div>
                    <p className="text-sm font-semibold text-secondary-900 dark:text-white">{posts[0].author}</p>
                    <p className="text-xs text-secondary-500 dark:text-secondary-400">{posts[0].date}</p>
                  </div>
                </div>
                <Link to={`/blog/${posts[0].id}`}>
                  <Button variant="outline" size="sm" className="rounded-full hover:bg-primary-600 hover:text-white dark:hover:bg-primary-500">
                    Read More
                  </Button>
                </Link>
              </div>
            </div>
          </div>
        </div>

        {/* Recent Posts Grid */}
        <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          {posts.slice(1).map((post, index) => (
            <div
              key={post.id}
              className="bg-white dark:bg-secondary-900 rounded-xl overflow-hidden border border-secondary-200 dark:border-secondary-800 hover:border-primary-500/50 transition-all duration-300 group animate-fade-in flex flex-col"
              style={{ animationDelay: `${index * 100}ms` }}
            >
              <div className="h-48 overflow-hidden relative">
                <div className="absolute inset-0 bg-secondary-900/10 dark:bg-secondary-950/20 group-hover:bg-transparent transition-colors z-10"></div>
                <img
                  src={post.image}
                  alt={post.title}
                  className="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700"
                />
                <div className="absolute top-4 left-4 z-20">
                  <span className="px-3 py-1 rounded-full bg-white/90 dark:bg-secondary-950/90 backdrop-blur-sm text-xs font-bold text-primary-600 dark:text-primary-400 border border-primary-500/30">
                    {post.category}
                  </span>
                </div>
              </div>
              <div className="p-6 flex-grow flex flex-col">
                <div className="flex items-center gap-2 text-xs text-secondary-500 dark:text-secondary-400 mb-3">
                  <Calendar className="w-3 h-3" />
                  <span>{post.date}</span>
                  <span className="mx-1">•</span>
                  <span>{post.readTime}</span>
                </div>
                <h3 className="text-xl font-bold text-secondary-900 dark:text-white mb-3 group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                  <Link to={`/blog/${post.id}`}>{post.title}</Link>
                </h3>
                <p className="text-secondary-600 dark:text-secondary-400 text-sm mb-4 flex-grow">
                  {post.excerpt}
                </p>
                <div className="pt-4 border-t border-secondary-200 dark:border-secondary-800 flex items-center justify-between">
                  <div className="flex items-center gap-2 text-sm font-medium text-secondary-700 dark:text-secondary-300">
                    <User className="w-4 h-4 text-primary-500" />
                    {post.author}
                  </div>
                  <Link to={`/blog/${post.id}`} className="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 font-medium text-sm flex items-center gap-1 group-hover:translate-x-1 transition-transform">
                    Read <ArrowRight className="w-3 h-3" />
                  </Link>
                </div>
              </div>
            </div>
          ))}
        </div>

        {/* Pagination / View All */}
        <div className="mt-16 text-center">
          <Button size="lg" className="px-8">View All Articles</Button>
        </div>

      </div>
    </div>
  );
};

export default Blog;
