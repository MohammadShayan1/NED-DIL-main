# 🎓 DIL NED University - Directorate of Industrial Liaison

<div align="center">

![DIL Logo](https://dil.neduet.edu.pk/assets/images/dillogo.png)

### Bridging Academia, Industry & Government for a Better Tomorrow

[![Website](https://img.shields.io/badge/Website-dil.neduet.edu.pk-blue?style=for-the-badge)](https://dil.neduet.edu.pk)
[![PHP](https://img.shields.io/badge/PHP-7.4+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Bootstrap](https://img.shields.io/badge/Bootstrap-5.0+-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)](https://getbootstrap.com)
[![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](LICENSE)

</div>

---

## 🌟 About DIL

The **Directorate of Industrial Liaison (DIL)** is NED University's flagship initiative, established in 1997 to foster collaboration between academia, industry, and government. DIL serves as a bridge connecting students with real-world opportunities, facilitating internships, job placements, industrial visits, and research collaborations.

### 🎯 Mission
To create sustainable partnerships between NED University and the industrial sector, ensuring students receive practical exposure while meeting industry workforce needs.

### 🔮 Vision
To be the leading platform for academia-industry collaboration in Pakistan, contributing to national development through skilled human resource development.

---

## ✨ Features

### 🏠 **Public Portal**
- **Responsive Design** - Optimized for all devices (mobile, tablet, desktop)
- **SEO Optimized** - Comprehensive search engine optimization for better visibility
- **Modern UI/UX** - Clean, professional design with Bootstrap 5
- **Accessibility** - WCAG 2.1 compliant for inclusive user experience

### 👥 **Student Services**
- 📋 **Internship Programs** - Browse and apply for industry internships
- 💼 **Job Opportunities** - Access fresh graduate and experienced positions
- 🏭 **Industrial Visits** - Explore industry facilities and operations
- 📚 **FYDPs** - Final Year Design Projects collaboration platform
- 📰 **Newsletter** - Stay updated with latest opportunities

### 🛠️ **Admin Dashboard**
- 📊 **Statistics Overview** - Real-time analytics and insights
- 👨‍💼 **Employee Management** - Complete CRUD operations with data validation
- 📈 **Database Monitoring** - Health checks and performance metrics
- 🔒 **Secure Authentication** - Role-based access control
- 📱 **Mobile Responsive** - Full functionality on all devices

### 🔍 **SEO & Analytics**
- 🗺️ **XML Sitemap** - Automated sitemap generation
- 🤖 **Robots.txt** - Search engine crawler optimization
- 📈 **Google Analytics** - Comprehensive tracking and insights
- 🏷️ **Structured Data** - Rich snippets for better search results
- 📱 **Social Media Integration** - Open Graph and Twitter Cards

---

## 🚀 Technology Stack

### **Backend**
- ![PHP](https://img.shields.io/badge/PHP-777BB4?style=flat&logo=php&logoColor=white) **PHP 7.4+** - Server-side scripting
- ![MySQL](https://img.shields.io/badge/MySQL-4479A1?style=flat&logo=mysql&logoColor=white) **MySQL 8.0+** - Database management
- ![Apache](https://img.shields.io/badge/Apache-D22128?style=flat&logo=apache&logoColor=white) **Apache/WAMP** - Web server

### **Frontend**
- ![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat&logo=html5&logoColor=white) **HTML5** - Semantic markup
- ![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat&logo=css3&logoColor=white) **CSS3** - Responsive styling
- ![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat&logo=javascript&logoColor=black) **JavaScript** - Interactive functionality
- ![Bootstrap](https://img.shields.io/badge/Bootstrap-7952B3?style=flat&logo=bootstrap&logoColor=white) **Bootstrap 5** - UI framework

### **Tools & Libraries**
- ![FontAwesome](https://img.shields.io/badge/Font%20Awesome-339AF0?style=flat&logo=fontawesome&logoColor=white) **Font Awesome** - Icons
- ![Google](https://img.shields.io/badge/Google%20Analytics-E37400?style=flat&logo=google%20analytics&logoColor=white) **Google Analytics** - Web analytics

---

## 📁 Project Structure

```
DIL/
├── 📂 admin/                    # Admin dashboard and management
│   ├── 🏠 index.php           # Dashboard home with statistics
│   ├── 👥 employee_*.php      # Employee management system
│   ├── 🔧 init-database.php   # Database initialization
│   └── 📊 team-management.php # Team overview
├── 📂 assets/                  # Static resources
│   ├── 🎨 css/               # Stylesheets
│   ├── 📷 images/            # Image assets
│   └── 📜 js/                # JavaScript files
├── 🏠 home.php                # Homepage
├── ℹ️ About.php               # About page
├── 💼 Jobs.php                # Job listings
├── 📋 Internships.php         # Internship programs
├── 🏭 IndustrialVisit.php     # Industrial visits
├── 📰 Newsletter.php          # Newsletter management
├── 🔗 header.php              # Global header component
├── 🦶 footer.php              # Global footer component
├── ⚙️ config.php              # Database configuration
├── 🔍 seo-config.php          # SEO configuration
├── 📈 analytics-seo.php       # Google Analytics integration
├── 🗺️ sitemap.xml            # Search engine sitemap
├── 🤖 robots.txt             # Crawler instructions
└── 📄 manifest.json          # PWA manifest
```

---

## 🛠️ Installation & Setup

### **Prerequisites**
- WAMP/XAMPP/LAMP server
- PHP 7.4 or higher
- MySQL 8.0 or higher
- Web browser (Chrome, Firefox, Safari, Edge)

### **Step 1: Clone Repository**
```bash
git clone https://github.com/MohammadShayan1/NED-DIL.git
cd NED-DIL
```

### **Step 2: Database Setup**
1. Create a MySQL database named `DIL`
2. Import the database schema:
   ```sql
   CREATE DATABASE DIL;
   USE DIL;
   ```

### **Step 3: Configuration**
1. Update database credentials in `config.php`:
   ```php
   $servername = "localhost";
   $username = "root";
   $password = "";
   $dbname = "DIL";
   ```

2. Configure SEO settings in `seo-config.php`:
   ```php
   // Update domain and analytics codes
   'canonical' => 'https://your-domain.com/',
   'ga_tracking_id' => 'GA-XXXXXXX-X'
   ```

### **Step 4: Deploy**
1. Copy files to your web server directory:
   - **WAMP**: `C:\wamp64\www\DIL\`
   - **XAMPP**: `C:\xampp\htdocs\DIL\`
   - **Production**: Your hosting provider's public_html

2. Set proper file permissions (Linux/Unix):
   ```bash
   chmod 755 DIL/
   chmod 644 DIL/*.php
   ```

### **Step 5: Initialize Database**
Visit `http://localhost/DIL/admin/init-database.php` to set up database tables.

---

## 📱 Responsive Design

The website is fully responsive with optimized layouts for:

| Device Type | Screen Size | Features |
|-------------|-------------|----------|
| 📱 **Mobile** | < 576px | Touch-optimized interface, simplified navigation |
| 📱 **Mobile Large** | 576px - 767px | Enhanced mobile experience |
| 💻 **Tablet** | 768px - 991px | Balanced layout with sidebar functionality |
| 💻 **Desktop** | 992px - 1199px | Full-featured interface |
| 🖥️ **Large Desktop** | 1200px+ | Spacious layout with extended features |

---

## 🔍 SEO Features

### **On-Page SEO**
- ✅ Meta titles and descriptions
- ✅ Semantic HTML structure
- ✅ Image alt attributes
- ✅ Internal linking strategy
- ✅ Page speed optimization

### **Technical SEO**
- ✅ XML sitemap generation
- ✅ Robots.txt optimization
- ✅ Structured data markup
- ✅ Open Graph tags
- ✅ Twitter Card integration

### **Local SEO**
- ✅ Pakistan/Karachi geo-targeting
- ✅ University-specific keywords
- ✅ Local business schema

---

## 👨‍💼 Admin Features

### **Dashboard Overview**
- 📊 Real-time statistics
- 📈 Recent activities feed
- 🗄️ Database health monitoring
- 📱 Mobile-responsive interface

### **Employee Management**
- ➕ Add new employees with validation
- ✏️ Edit existing employee records
- 🗑️ Delete employees with confirmation
- 🔄 Move employees between departments
- 📧 Email field with NULL handling

### **Content Management**
- 📝 Dynamic content updates
- 📷 Image upload and management
- 📊 Analytics integration
- 🔒 Secure authentication

---

## 🚨 Troubleshooting

### **Common Issues**

**Database Connection Error**
```php
// Check config.php settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "DIL";
```

**Employee Email NULL Error**
```php
// Fixed with null coalescing operator
htmlspecialchars($row['email'] ?? 'N/A')
```

**404 Errors**
- Ensure `.htaccess` is properly configured
- Check file permissions
- Verify URL rewriting is enabled

**Responsive Issues**
- Clear browser cache
- Check `home.css` for media queries
- Validate Bootstrap integration

---

## 🤝 Contributing

We welcome contributions to improve the DIL website! Here's how you can help:

### **Development Workflow**
1. **Fork** the repository
2. **Create** a feature branch: `git checkout -b feature/amazing-feature`
3. **Commit** your changes: `git commit -m 'Add amazing feature'`
4. **Push** to branch: `git push origin feature/amazing-feature`
5. **Open** a Pull Request

### **Contribution Guidelines**
- Follow PHP PSR standards
- Write clear, commented code
- Test all functionality
- Update documentation
- Ensure mobile responsiveness

---

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

---

## 👥 Team

### **Development Team**
- **Project Lead**: DIL Administration
- **Backend Developer**: PHP/MySQL Expert
- **Frontend Developer**: HTML/CSS/JS Specialist
- **SEO Specialist**: Digital Marketing Expert

### **Academic Supervision**
- **NED University**: Computer & Information Systems Engineering Department
- **DIL Office**: Industrial Liaison Team

---

## 📞 Contact & Support

### **DIL Office**
- 🏢 **Address**: NED University of Engineering & Technology, Karachi, Pakistan
- 📧 **Email**: dil@neduet.edu.pk
- 📞 **Phone**: +92-21-99261261
- 🌐 **Website**: [dil.neduet.edu.pk](https://dil.neduet.edu.pk)

### **Technical Support**
- 🐛 **Bug Reports**: Create an issue on GitHub
- 💡 **Feature Requests**: Open a discussion
- 📚 **Documentation**: Check the wiki section

---

## 🏆 Achievements

- ✅ **100% Responsive** design across all devices
- ✅ **SEO Optimized** for better search rankings
- ✅ **Accessibility Compliant** WCAG 2.1 standards
- ✅ **Database Integrity** with proper error handling
- ✅ **Modern UI/UX** with Bootstrap 5
- ✅ **Performance Optimized** for fast loading

---

## 🔄 Version History

| Version | Date | Features |
|---------|------|----------|
| **v2.0** | 2024 | SEO optimization, responsive design, admin dashboard |
| **v1.5** | 2023 | Employee management, database integration |
| **v1.0** | 2022 | Basic website functionality |

---

## 🎉 Acknowledgments

- **NED University** for institutional support
- **Bootstrap Team** for the excellent UI framework
- **FontAwesome** for beautiful icons
- **Google** for analytics and SEO tools

---

<div align="center">

### 🌟 Star this repository if you found it helpful!

**Made with ❤️ for NED University & Pakistan's Educational Excellence**

[⬆️ Back to Top](#-dil-ned-university---directorate-of-industrial-liaison)

</div>
