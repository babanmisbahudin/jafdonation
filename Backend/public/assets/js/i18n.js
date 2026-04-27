(function () {
  var T = {
    id: {
      /* Nav */
      'nav.home'    : 'Beranda',
      'nav.berita'  : 'Berita',
      'nav.galeri'  : 'Galeri',
      'nav.donasi'  : 'Donasi',
      'nav.benah'   : 'Benah Kampung',
      'nav.kontak'  : 'Kontak',
      'nav.search'  : 'Cari artikel...',

      /* Hero */
      'hero.donate'   : 'Donasi Sekarang',
      'hero.account'  : 'Rekening Donasi:',
      'hero.slide1.tag'  : 'Tahun Tanah — Triennale Seni & Budaya 2025',
      'hero.slide1.t1'   : 'SENI UNTUK',
      'hero.slide1.t2'   : 'KEHIDUPAN',
      'hero.slide1.desc' : 'Jatiwangi Art Factory (JAF) adalah komunitas seni berbasis di Jatiwangi, Majalengka, yang berkomitmen mengubah daerah melalui praktik seni kontemporer berbasis bumi.',
      'hero.slide1.q'    : '"Seni memiliki vitalitas untuk mengubah suatu daerah, membangun kapasitas masyarakat, dan membawa denyut nadi komunitas lokal untuk beresonansi di panggung global."',
      'hero.slide2.tag'  : 'Rampak Genteng — Ritual Musik Komunal',
      'hero.slide2.t1'   : 'TANAH UNTUK',
      'hero.slide2.t2'   : 'SEMUA ORANG',
      'hero.slide2.desc' : 'Rampak Genteng adalah ritual kolektif memainkan genteng atap sebagai instrumen musik, merayakan warisan budaya tanah liat Jatiwangi bersama warga.',
      'hero.slide2.q'    : '"Tanah adalah ingatan, dan seni adalah cara kita berbicara dengannya."',
      'hero.slide3.tag'  : 'Terracotta Triennale — Pameran Seni Dunia',
      'hero.slide3.t1'   : 'BERKARYA DARI',
      'hero.slide3.t2'   : 'BUMI KITA',
      'hero.slide3.desc' : 'Terracotta Triennale menghadirkan seniman lokal dan internasional yang berkolaborasi mengeksplorasi material tanah liat sebagai medium seni kontemporer.',
      'hero.slide3.q'    : '"Dari tanah kita lahir, dari tanah kita berkarya, untuk tanah kita kembali."',
      'hero.slide4.tag'  : 'Magister Reka Budaya — Program Akademik',
      'hero.slide4.t1'   : 'BANGUN',
      'hero.slide4.t2'   : 'BUDAYA LOKAL',
      'hero.slide4.desc' : 'Program Magister Reka Budaya bersama Universitas Padjadjaran mengajak generasi muda merencanakan dan membangun ekosistem budaya daerah yang berkelanjutan.',
      'hero.slide4.q'    : '"Komunitas yang kuat tumbuh dari akar budayanya sendiri."',

      /* About */
      'about.title' : 'Bersama Kita Bangun Ekosistem Seni & Budaya',
      'about.desc'  : 'Sejak 2005, Jatiwangi Art Factory (JAF) hadir sebagai ruang komunitas seni di Jatiwangi, Majalengka, Jawa Barat — merayakan tradisi lokal, mengeksplorasi material tanah, dan menghubungkan seniman daerah dengan panggung seni internasional.',

      /* Programs */
      'programs.title'   : 'Program JAF',
      'programs.more'    : 'Lihat Semua Program',
      'programs.support' : 'Dukung Residensi Seniman',

      /* News */
      'news.title'           : 'Berita dan Kisah Inspiratif',
      'news.more'            : 'Selengkapnya',
      'tab.berita'           : 'Berita',
      'tab.foto'             : 'Berita Foto',
      'tab.video'            : 'Video',
      'tab.pub'              : 'Publikasi',
      'tab.buletin'          : 'Buletin',
      'tab.humanis'          : 'Kisah Humanis',
      'tab.sehat'            : 'Info Sehat',
      'tab.resep'            : 'Resep Vegetarian',
      'tab.empty.pub'        : 'Belum ada publikasi terbaru.',
      'tab.empty.buletin'    : 'Belum ada buletin terbaru.',
      'tab.empty.humanis'    : 'Belum ada kisah humanis.',
      'tab.empty.sehat'      : 'Belum ada info sehat.',
      'tab.empty.resep'      : 'Belum ada resep vegetarian.',

      /* Video */
      'video.title' : 'Video',
      'video.more'  : 'Video Lainnya',
      'video.watch' : 'Tonton di YouTube',

      /* Quote */
      'quote.text'   : '"Seni memiliki vitalitas untuk mengubah suatu daerah, membangun kapasitas masyarakat, dan membawa denyut nadi komunitas lokal untuk beresonansi di panggung global."',
      'quote.author' : '— Jatiwangi Art Factory',

      /* Pages — shared */
      'breadcrumb.home' : 'Beranda',
      'page.loading'    : 'Memuat...',
      'btn.more'        : 'Selengkapnya',

      /* Berita page */
      'berita.heading'    : 'Berita & Kegiatan',
      'berita.sub'        : 'Kisah nyata kebaikan dari seluruh penjuru Indonesia',
      'berita.search'     : 'Cari berita...',
      'berita.loading'    : 'Memuat berita...',
      'berita.empty'      : 'Belum ada berita yang tersedia.',
      'cat.all'           : 'Semua Kategori',
      'cat.amal'          : 'Amal',
      'cat.kesehatan'     : 'Kesehatan',
      'cat.pendidikan'    : 'Pendidikan',
      'cat.budaya'        : 'Budaya Humanis',
      'cat.lingkungan'    : 'Lingkungan',
      'region.all'        : 'Semua Wilayah',

      /* Galeri page */
      'galeri.heading'  : 'Galeri',
      'galeri.sub'      : 'Dokumentasi kegiatan dan program Jatiwangi Art Factory',
      'galeri.all'      : 'Semua',
      'galeri.foto'     : 'Foto',
      'galeri.video'    : 'Video',
      'galeri.loading'  : 'Memuat galeri...',
      'galeri.empty'    : 'Belum ada konten galeri.',

      /* Donasi page */
      'donasi.heading'  : 'Donasi',
      'donasi.sub'      : 'Bersama membangun kebaikan',
      'donasi.btn'      : 'Donasi Sekarang',
      'donasi.amount'   : 'Jumlah Donasi',
      'donasi.name'     : 'Nama Donatur',
      'donasi.email'    : 'Email',
      'donasi.phone'    : 'Nomor HP',
      'donasi.pay'      : 'Bayar Sekarang',
      'donasi.anon'     : 'Donasi Anonim',
      'donasi.loading'  : 'Memuat program donasi...',

      /* Benah Kampung page */
      'benah.heading' : 'Benah Kampung',
      'benah.sub'     : 'Program pemberdayaan desa berbasis seni dan budaya',

      /* Kontak page */
      'kontak.heading' : 'Kontak',
      'kontak.sub'     : 'Hubungi kami',
      'kontak.name'    : 'Nama Lengkap',
      'kontak.email'   : 'Email',
      'kontak.subject' : 'Subjek',
      'kontak.message' : 'Pesan',
      'kontak.send'    : 'Kirim Pesan',

      /* Artikel page */
      'artikel.heading' : 'Artikel',
      'artikel.sub'     : 'Tulisan dan publikasi Jatiwangi Art Factory',
      'artikel.search'  : 'Cari artikel...',
      'artikel.loading' : 'Memuat artikel...',
      'artikel.empty'   : 'Belum ada artikel.',
    },

    en: {
      /* Nav */
      'nav.home'    : 'Home',
      'nav.berita'  : 'News',
      'nav.galeri'  : 'Gallery',
      'nav.donasi'  : 'Donation',
      'nav.benah'   : 'Village Restoration',
      'nav.kontak'  : 'Contact',
      'nav.search'  : 'Search articles...',

      /* Hero */
      'hero.donate'   : 'Donate Now',
      'hero.account'  : 'Donation Account:',
      'hero.slide1.tag'  : 'Year of Earth — Arts & Culture Triennale 2025',
      'hero.slide1.t1'   : 'ART FOR',
      'hero.slide1.t2'   : 'LIFE',
      'hero.slide1.desc' : 'Jatiwangi Art Factory (JAF) is an arts community based in Jatiwangi, Majalengka, committed to transforming the region through earth-based contemporary art practices.',
      'hero.slide1.q'    : '"Art has the vitality to transform a region, build community capacity, and bring the pulse of local communities to resonate on the global stage."',
      'hero.slide2.tag'  : 'Rampak Genteng — Communal Music Ritual',
      'hero.slide2.t1'   : 'LAND FOR',
      'hero.slide2.t2'   : 'EVERYONE',
      'hero.slide2.desc' : 'Rampak Genteng is a collective ritual of playing roof tiles as musical instruments, celebrating the clay cultural heritage of Jatiwangi together with residents.',
      'hero.slide2.q'    : '"Land is memory, and art is the way we speak to it."',
      'hero.slide3.tag'  : 'Terracotta Triennale — World Art Exhibition',
      'hero.slide3.t1'   : 'CREATING FROM',
      'hero.slide3.t2'   : 'OUR EARTH',
      'hero.slide3.desc' : 'Terracotta Triennale brings together local and international artists who collaborate to explore clay as a medium for contemporary art.',
      'hero.slide3.q'    : '"From the earth we are born, from the earth we create, to the earth we return."',
      'hero.slide4.tag'  : 'Master of Cultural Design — Academic Program',
      'hero.slide4.t1'   : 'BUILD',
      'hero.slide4.t2'   : 'LOCAL CULTURE',
      'hero.slide4.desc' : 'The Master of Cultural Design program together with Universitas Padjadjaran invites young generations to plan and build sustainable regional cultural ecosystems.',
      'hero.slide4.q'    : '"Strong communities grow from their own cultural roots."',

      /* About */
      'about.title' : 'Together We Build the Arts & Culture Ecosystem',
      'about.desc'  : 'Since 2005, Jatiwangi Art Factory (JAF) has been a community arts space in Jatiwangi, Majalengka, West Java — celebrating local traditions, exploring clay materials, and connecting local artists to the international stage.',

      /* Programs */
      'programs.title'   : 'JAF Programs',
      'programs.more'    : 'View All Programs',
      'programs.support' : 'Support Artist Residency',

      /* News */
      'news.title'           : 'News and Inspiring Stories',
      'news.more'            : 'More',
      'tab.berita'           : 'News',
      'tab.foto'             : 'Photo News',
      'tab.video'            : 'Video',
      'tab.pub'              : 'Publications',
      'tab.buletin'          : 'Bulletin',
      'tab.humanis'          : 'Human Stories',
      'tab.sehat'            : 'Health Info',
      'tab.resep'            : 'Vegetarian Recipes',
      'tab.empty.pub'        : 'No publications yet.',
      'tab.empty.buletin'    : 'No bulletins yet.',
      'tab.empty.humanis'    : 'No human stories yet.',
      'tab.empty.sehat'      : 'No health info yet.',
      'tab.empty.resep'      : 'No vegetarian recipes yet.',

      /* Video */
      'video.title' : 'Videos',
      'video.more'  : 'More Videos',
      'video.watch' : 'Watch on YouTube',

      /* Quote */
      'quote.text'   : '"Art has the vitality to transform a region, build community capacity, and bring the pulse of local communities to resonate on the global stage."',
      'quote.author' : '— Jatiwangi Art Factory',

      /* Pages — shared */
      'breadcrumb.home' : 'Home',
      'page.loading'    : 'Loading...',
      'btn.more'        : 'More',

      /* Berita page */
      'berita.heading'    : 'News & Activities',
      'berita.sub'        : 'Real stories of kindness from across Indonesia',
      'berita.search'     : 'Search news...',
      'berita.loading'    : 'Loading news...',
      'berita.empty'      : 'No news available yet.',
      'cat.all'           : 'All Categories',
      'cat.amal'          : 'Charity',
      'cat.kesehatan'     : 'Health',
      'cat.pendidikan'    : 'Education',
      'cat.budaya'        : 'Humanist Culture',
      'cat.lingkungan'    : 'Environment',
      'region.all'        : 'All Regions',

      /* Galeri page */
      'galeri.heading'  : 'Gallery',
      'galeri.sub'      : 'Documentation of Jatiwangi Art Factory activities and programs',
      'galeri.all'      : 'All',
      'galeri.foto'     : 'Photos',
      'galeri.video'    : 'Videos',
      'galeri.loading'  : 'Loading gallery...',
      'galeri.empty'    : 'No gallery content yet.',

      /* Donasi page */
      'donasi.heading'  : 'Donation',
      'donasi.sub'      : 'Together building goodness',
      'donasi.btn'      : 'Donate Now',
      'donasi.amount'   : 'Donation Amount',
      'donasi.name'     : "Donor's Name",
      'donasi.email'    : 'Email',
      'donasi.phone'    : 'Phone Number',
      'donasi.pay'      : 'Pay Now',
      'donasi.anon'     : 'Anonymous Donation',
      'donasi.loading'  : 'Loading donation programs...',

      /* Benah Kampung page */
      'benah.heading' : 'Village Restoration',
      'benah.sub'     : 'Village empowerment program based on arts and culture',

      /* Kontak page */
      'kontak.heading' : 'Contact',
      'kontak.sub'     : 'Get in touch with us',
      'kontak.name'    : 'Full Name',
      'kontak.email'   : 'Email',
      'kontak.subject' : 'Subject',
      'kontak.message' : 'Message',
      'kontak.send'    : 'Send Message',

      /* Artikel page */
      'artikel.heading' : 'Articles',
      'artikel.sub'     : 'Writings and publications by Jatiwangi Art Factory',
      'artikel.search'  : 'Search articles...',
      'artikel.loading' : 'Loading articles...',
      'artikel.empty'   : 'No articles yet.',
    }
  };

  window._i18n = T;

  window.setLang = function (lang) {
    if (!T[lang]) return;
    localStorage.setItem('lang', lang);
    document.documentElement.lang = lang;

    document.querySelectorAll('[data-i18n]').forEach(function (el) {
      var key = el.getAttribute('data-i18n');
      if (T[lang][key] !== undefined) el.textContent = T[lang][key];
    });

    document.querySelectorAll('[data-i18n-ph]').forEach(function (el) {
      var key = el.getAttribute('data-i18n-ph');
      if (T[lang][key] !== undefined) el.placeholder = T[lang][key];
    });

    document.querySelectorAll('.lang-btn').forEach(function (btn) {
      btn.classList.toggle('active', btn.dataset.lang === lang);
    });
  };

  window.initLang = function () {
    var lang = localStorage.getItem('lang') || 'id';
    setLang(lang);
  };
})();
