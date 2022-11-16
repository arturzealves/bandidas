<footer class="bg-gray-200 py-5">
    <div class="max-w-screen-xl my-5 mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-flow-row-dense grid-cols-4 gap-5">
            <div class="col-span-2">
                <h3 class="text-xl">| logotipo |</h3>
                <br>
                <br>
                <p>Olha mas que belo sítio para se escrever um breve resumo acerca do que raio vem a ser este site.
                Servirá para aquelas pessoas que passaram pela página toda até cá abaixo e ainda assim não
                ficaram a entender do que isto se trata.</p>
                <br>
                <div class="text-sm">
                    Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
                </div>
            </div>
            <div class="">
                <h3 class="text-xl">Contact</h3>
                <br>
                <ul class="list-none">
                    <li>
                        <a href="#">Support</a>
                    </li>
                    <li>
                        <a href="#">Press</a>
                    </li>
                    <li>
                        <a href="#">Create your event!</a>
                    </li>
                </ul>
            </div>
            <div>
                <h3 class="text-xl">Legal</h3>
                <br>
                <ul class="list-none">
                    <li>
                        <a href="#">FAQ</a>
                    </li>
                    <li>
                        <a target="_blank" href="{{ route('terms.show') }}">Terms of Service</a>
                    </li>
                    <li>
                        <a target="_blank" href="{{ route('policy.show') }}">Privacy Policy</a>
                    </li>
                    <li>
                        <a href="#">Cookies Policy</a>
                    </li>
                    <li>
                        <a href="#">Cookies Management</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
