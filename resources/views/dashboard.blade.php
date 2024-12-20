<x-app-layout>

    <section class="hero-section bg-info ">
        <div class="overlay position-absolute top-0 start-0 w-100 h-100"></div>
        <div class="container1 position-relative z-1 text-center text-white py-5">
            <h1 class="display-4 fw-bold"><span>Welcome to our </span><br>  Online Quiz Platform </h1>
            <p class="lead my-4">Enhance your skills and challenge your abilities with exciting quizzes on a wide range of topics !</p>
            <a  href="{{ route('quizzes.by_topic', 0) }}" class="btn btn-lg btn-outline-light">Start a Quiz Now</a>
        </div>

    </section>

    <section class="features-section">
        <div class="container">
            <h2 class="text-center mb-4 mb-5">Why Choose Our Quiz Platform?</h2>
            <div class="row text-center">
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-2xl border-0 transition-hover">
                        <div class="card-body py-5">
                            <div class="icon mb-3">
                                <i class="fas fa-chart-line fa-3x text-primary"></i>
                            </div>
                            <h5 class="card-title fw-bold  my-3">Track Your Progress</h5>
                            <p class="card-text">Monitor your quiz history and see your improvement over time with detailed stats.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-2xl border-0 transition-hover">
                        <div class="card-body py-5">
                            <div class="icon mb-3">
                                <i class="fas fa-book-open fa-3x text-success"></i>
                            </div>
                            <h5 class="card-title fw-bold my-3">Various Topics</h5>
                            <p class="card-text">Pick from a wide variety of topics and sharpen your skills with expert-designed quizzes.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 mb-4">
                    <div class="card shadow-2xl border-0 transition-hover">
                        <div class="card-body py-5">
                            <div class="icon mb-3">
                                <i class="fas fa-stopwatch fa-3x text-warning"></i>
                            </div>
                            <h5 class="card-title fw-bold  my-3">Time-Limited Quizzes</h5>
                            <p class="card-text">Challenge yourself with time-limited quizzes that push your limits!</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<section id="about-us" class="py-5 bg-light">
    <div class="container">
      <div class="row justify-content-center text-center">
        <div class="col-lg-8 col-md-10">
          <h2 class="mb-4 display-4 font-weight-bold text-white">About Us</h2>
        </div>
      </div>

      <div class=" d-flex flex-lg-row flex-column justify-content-between align-items-center mt-4">

        <div class=" text-center mb-3">
          <div class="icon-box">
            <i class="fas fa-graduation-cap fa-3x text-primary mb-3"></i>
            <h5 class="mb-3">Our Mission</h5>
            <p class="text-muted">
                To make learning interactive, and accessible for everyone through our quiz platform, empowering individuals to enhance their knowledge and skills.
            </p>
          </div>
        </div>

        <div class="text-center mb-3">
          <div class="icon-box">
            <i class="fas fa-lightbulb fa-3x text-primary mb-3"></i>
            <h5 class="mb-3">Our Vision</h5>
            <p class="text-muted">
                To inspire lifelong learning and personal growth by providing users with engaging, meaningful, and enjoyable quizzes that enhance knowledge.
            </p>
          </div>
        </div>

        <div class=" text-center mb-3">
            <div class="icon-box">
                <i class="fas fa-users fa-3x text-primary mb-3"></i>
                <h5 class="mb-3">Our Team</h5>
                <p class="text-muted">
                    We are a team at the Digital Egypt Pioneers Initiative. we collaborate to develop solutions that make learning more interactive for everyone.
                </p>
            </div>
        </div>
      </div>
    </div>
  </section>

  @if (!auth()->check())
        <section class="cta-section text-center">
            <div class="container">
                <h2 class="display-5 fw-bold mb-4">Ready to Test Your Knowledge?</h2>
                <p class="lead">Sign up or log in to take a quiz and track your progress!</p>
                <a href="{{ route('login') }}" class="btn btn-lg btn-outline-primary px-4 py-3 mt-3">Get Started</a>
            </div>
        </section>
    @endif


</x-app-layout>
