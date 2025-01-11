<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    <!-- Link do Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Link do CSS -->
    <link rel="stylesheet" href="/css/estilo.css">

    <script src="script.js"></script>
    
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom">
        <a class="navbar-brand" href="/"><img src="/img/Tlogo3.png" alt=""></a> <!-- Logo à esquerda -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarNav"> <!-- Menu à direita -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="/">Página Inicial</a>
                </li>
                @guest
                <li class="nav-item">
                    <a class="nav-link" href="/Escola/MainEscola">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Estudante/MainEstudante">Estudante</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Responsavel/MainResponsavel">Responsavel</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/Motorista/MainMotorista">Motorista</a>
                </li>
                <li class="nav-item">
                    <a id="login" class="btn" href="/auth/login">Login</a>
                </li>
                @endguest
                @auth
                <li class="nav-item">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn ">Logout</button>
                    </form>
                </li>
                @endauth
            </ul>
        </div>
    </nav>
    <main>
        <div class="container-fuid">
            @if(session('sucess'))
                <div class="sucess">
                    <p>{{session('sucess')}}<ion-icon name="checkmark-outline"></ion-icon></p>
                </div>
            @elseif(session ('error'))
                <div class="error">
                    <p>{{session('error')}}<ion-icon name="bug-outline"></ion-icon></p>
                </div>
            @elseif(session('alert'))
                <div class="alert">
                    <p>{{session('alert')}}<ion-icon name="alert-outline"></ion-icon></p>
                </div>
            @endif
            @yield('content') 
        </div>
    </main>
    <footer>
        <div class="TabelasFooter">
            <!-- Primeira coluna: Informação (mais larga) -->
            <div class="TabelaFooter">
                <h5>Informação</h5>
                <ul>
                    <li><a href="#" class="footer-link">Sobre nós</a></li>
                    <li><a href="#" class="footer-link">Política e Privacidade</a></li>
                    <li><a href="#" class="footer-link">Termos e Condições</a></li>
                </ul>
            </div>

            <!-- Segunda coluna: Contactos (mais estreita) -->
            <div class="TabelaFooter">
                <h5 >Contactos</h5>
                <ul>
                    <li><a href="#" class="footer-link">TwendenawaLda <ion-icon name="logo-instagram" ></ion-icon></a></li>
                    <li><a href="#" class="footer-link"> Whatsapp <ion-icon name="logo-whatsapp" ></ion-icon></a></li>
                    <li><a href="#" class="footer-link">Telegram <ion-icon name="paper-plane-outline"></ion-icon></a></li>
                </ul>
            </div>
        </div>

        <!-- Linha Divisória e Copyright -->
        <hr>
        <div class="text-center mt-2">
            &copy; Twendenawa 2024
        </div>
    </footer>
    <!-- Scripts do Bootstrap e jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    {{--IONICONS ABAIXO--}}
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    @yield('scripts')
</body>
</html>
