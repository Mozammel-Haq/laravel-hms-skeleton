import React from 'react';
import { useParams, Link } from 'react-router-dom';
import { ArrowLeft, Calendar, User, Tag } from 'lucide-react';

const BlogPost = () => {
  const { id } = useParams();

  // Mock data
  const posts = [
    {
      id: 1,
      title: "Understanding Heart Health: Tips for a Better Life",
      category: "Cardiology",
      date: "Jan 15, 2026",
      author: "Dr. Sarah Ahmed",
      content: `
        <p class="mb-4">Heart disease remains one of the leading causes of death worldwide, but many risk factors are preventable. In this article, we explore practical steps you can take to improve your cardiovascular health.</p>

        <h3 class="text-xl font-bold mb-2 mt-6">1. Maintain a Healthy Diet</h3>
        <p class="mb-4">Eating a balanced diet rich in fruits, vegetables, whole grains, and lean proteins can significantly reduce your risk of heart disease. Limit your intake of saturated fats, sodium, and added sugars.</p>

        <h3 class="text-xl font-bold mb-2 mt-6">2. Regular Exercise</h3>
        <p class="mb-4">Aim for at least 150 minutes of moderate-intensity aerobic activity or 75 minutes of vigorous activity each week. Regular physical activity strengthens your heart muscle and improves blood circulation.</p>

        <h3 class="text-xl font-bold mb-2 mt-6">3. Manage Stress</h3>
        <p class="mb-4">Chronic stress can contribute to high blood pressure and other heart-related issues. Practice stress-reduction techniques such as meditation, deep breathing exercises, or yoga.</p>

        <h3 class="text-xl font-bold mb-2 mt-6">4. Regular Check-ups</h3>
        <p class="mb-4">Regular health screenings can help detect risk factors like high blood pressure, high cholesterol, and diabetes early. Consult with your healthcare provider to determine the appropriate screening schedule for you.</p>
      `
    },
    {
      id: 2,
      title: "The Importance of Mental Health Awareness",
      category: "Psychology",
      date: "Jan 10, 2026",
      author: "Dr. David Kim",
      content: `
        <p class="mb-4">Mental health is just as important as physical health. It affects how we think, feel, and act. It also helps determine how we handle stress, relate to others, and make choices.</p>
        <p class="mb-4">Promoting mental health awareness helps to reduce the stigma associated with mental illness and encourages people to seek help when needed.</p>
      `
    },
    {
      id: 3,
      title: "Nutrition Tips for a Stronger Immune System",
      category: "Wellness",
      date: "Jan 05, 2026",
      author: "Nutritionist Emily White",
      content: `
        <p class="mb-4">A strong immune system is your body's best defense against illness. While no single food can prevent sickness, eating a variety of nutrient-rich foods can help support your immune function.</p>
        <p class="mb-4">Focus on foods high in Vitamin C, Vitamin D, and Zinc. Citrus fruits, leafy greens, nuts, and seeds are excellent choices to include in your daily diet.</p>
      `
    }
  ];

  const post = posts.find(p => p.id === parseInt(id)) || posts[0];

  return (
    <div className="pt-20 min-h-screen bg-secondary-50 dark:bg-secondary-950 transition-colors duration-300">
      <div className="bg-white dark:bg-secondary-900 border-b border-secondary-200 dark:border-white/5 py-12">
        <div className="container mx-auto px-4 max-w-4xl">
          <Link to="/blog" className="inline-flex items-center text-secondary-500 hover:text-primary-600 mb-6 transition-colors">
            <ArrowLeft className="w-4 h-4 mr-2" />
            Back to Blog
          </Link>

          <div className="flex flex-wrap gap-4 mb-4 text-sm">
            <span className="flex items-center text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20 px-3 py-1 rounded-full">
              <Tag className="w-3 h-3 mr-1" />
              {post.category}
            </span>
            <span className="flex items-center text-secondary-500 dark:text-secondary-400">
              <Calendar className="w-3 h-3 mr-1" />
              {post.date}
            </span>
            <span className="flex items-center text-secondary-500 dark:text-secondary-400">
              <User className="w-3 h-3 mr-1" />
              {post.author}
            </span>
          </div>

          <h1 className="text-3xl md:text-4xl font-bold text-secondary-900 dark:text-white leading-tight">
            {post.title}
          </h1>
        </div>
      </div>

      <div className="container mx-auto px-4 max-w-4xl py-12">
        <div className="bg-white dark:bg-secondary-900 rounded-2xl p-8 md:p-12 border border-secondary-200 dark:border-secondary-800">
          <div
            className="prose prose-lg dark:prose-invert max-w-none text-secondary-600 dark:text-secondary-300"
            dangerouslySetInnerHTML={{ __html: post.content }}
          />
        </div>
      </div>
    </div>
  );
};

export default BlogPost;
