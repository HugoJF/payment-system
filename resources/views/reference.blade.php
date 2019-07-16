<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title>Laravel</title>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    
    <!-- Styles -->
    <link href="{{ mix('/css/app.css') }}" rel="stylesheet">
</head>
<body class="trans bg-grey-light font-sans">
<div id="root"></div>
<script src="{{ mix('/js/app.tsx') }}"></script>

<div class="flex flex-col items-center justify-center p-6 md:p-12 my-32">
    <div class="relative flex justify-center w-full m-auto">
        <div style="top: 0" class="absolute hidden -translate-50 self-center p-4 justify-center items-center bg-white rounded-full shadow sm:flex">
            <img class="h-32 w-32 rounded-full" src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/45/45be9bd313395f74762c1a5118aee58eb99b4688_full.jpg"/>
        </div>
    </div>
    <div class="flex flex-col lg:w-1/2 xl:w-1/3 w-full justify-center bg-grey-lightest border border-blue-dark rounded-lg shadow-lg overflow-hidden">
        <div class="flex flex-col p-4 justify-center items-center sm:p-6">
            <div class="flex flex-col self-stretch items-center justify-between sm:flex-row sm:items-start">
                <h2 class="text-grey-dark text-lg font-mono font-medium">#23858283</h2>
                <span class="uppercase mt-2 py-2 px-3 text-sm text-blue-lightest font-bold bg-blue-dark rounded-lg sm:mt-0">Aguardando</span>
            </div>
            <p class="mt-12 text-grey-dark text-sm ">30 dias de VIP nos servidores de CS:GO</p>
            
            <h2 class="mt-12 uppercase text-grey-dark text-center text-2xl font-normal tracking-wide">Valor total</h2>
            <p class="flex mt-8 pb-4 justify-center items-baseline text-center text-5xl">
                <span class="mr-1 text-3xl text-grey font-normal">R$</span>
                <span class="text-grey-darkest font-semibold">8,00</span>
            </p>
            
            <a href="#" class="mt-4 py-4 px-12 bg-blue no-underline text-grey-lighter text-xl font-bold rounded-lg shadow shadow-3d-blue-sm hover:bg-blue-dark">Pagar</a>
        </div>
        
        <div class="h-4 w-full">
            <div class="h-full w-full bg-blue-dark w-64"></div>
        </div>
    </div>
    
    <div class="relative flex justify-center w-full m-auto">
        <div class="absolute hidden -translate-50 self-center p-4 justify-center items-center bg-white rounded-full shadow sm:flex">
            <img class="h-32 w-32 rounded-full" src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/45/45be9bd313395f74762c1a5118aee58eb99b4688_full.jpg"/>
        </div>
    </div>
    <div class="flex flex-col mt-32 lg:w-1/2 xl:w-1/3 w-full justify-center bg-grey-lightest border border-blue-dark rounded-lg shadow-lg overflow-hidden">
        <div class="flex flex-col p-4 justify-center items-center sm:p-6">
            <div class="flex flex-col self-stretch items-center justify-between sm:flex-row sm:items-start">
                <h2 class="h-5 w-28 bg-grey-light rounded-lg text-lg font-mono font-medium"></h2>
                <span class="uppercase mt-2 py-2 px-3 h-8 w-32 text-sm text-blue-lightest font-bold bg-grey-light rounded-lg sm:mt-0"></span>
            </div>
            <p class="mt-12 h-4 w-64 bg-grey-light rounded-lg text-sm"></p>
            
            <h2 class="mt-12 h-8 w-48 uppercase bg-grey-light text-center text-2xl font-normal rounded-lg tracking-wide"></h2>
            <p class="flex mt-8 pb-4 h-12 w-32 bg-grey-light rounded-lg justify-center items-baseline text-center text-5xl"></p>
            
            <a href="#" class="mt-4 h-12 w-48 py-14 px-12 no-underline bg-grey-light text-xl font-bold rounded-lg"></a>
        </div>
        
        <div class="h-4 w-full">
            <div class="h-full w-full bg-blue-dark w-64"></div>
        </div>
    </div>
    
    
    <!-- 404 -->
    <div class="relative flex justify-center w-full m-auto">
        <div class="absolute hidden -translate-50 self-center p-4 justify-center items-center bg-white rounded-full shadow sm:flex">
            <img class="h-32 w-32 rounded-full" src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/45/45be9bd313395f74762c1a5118aee58eb99b4688_full.jpg"/>
        </div>
    </div>
    <div class="flex flex-col mt-32 lg:w-1/2 xl:w-1/3 w-full justify-center bg-grey-lightest border border-grey-dark rounded-lg shadow-lg overflow-hidden">
        <div class="flex flex-col h-64 p-4 justify-center items-center sm:p-6">
            <h1 class="font-thin text-grey-darkest text-8xl tracking-wider">404</h1>
            <p class="font-hairline text-grey-darker text-4xl">Not Found</p>
        </div>
        
        <div class="h-4 w-full">
            <div class="h-full w-full bg-grey-dark w-64"></div>
        </div>
    </div>
    
    
    <div class="flex flex-col mt-32 lg:w-1/2 xl:w-1/3 w-full justify-center bg-grey-lightest border border-yellow-dark rounded-lg shadow-lg overflow-hidden">
        <div class="absolute hidden -translate-50 self-center p-4 justify-center items-center bg-white rounded-full shadow sm:flex" style="margin-top: -64px">
            <img class="h-32 w-32 rounded-full" src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/45/45be9bd313395f74762c1a5118aee58eb99b4688_full.jpg"/>
        </div>
        <div class="flex flex-col p-4 justify-center items-center sm:p-6">
            <div class="flex flex-col self-stretch items-center justify-between sm:flex-row sm:items-start">
                <h2 class="text-grey-dark text-lg font-mono font-medium">#23858283</h2>
                <span class="uppercase mt-2 py-2 px-3 text-sm text-yellow-darkest font-bold bg-yellow-dark rounded-lg sm:mt-0">Pendente</span>
            </div>
            
            <h2 class="mt-16 uppercase text-grey-dark text-center text-2xl font-normal tracking-wide">Aguardando tradeoffer</h2>
            
            <img class="mt-4 w-20 h-20" src="https://www.advisory.com/assets/responsive/images/loading1.gif">
        
        
        </div>
        
        <div class="h-4 w-full">
            <div class="h-full w-full bg-yellow-dark w-64"></div>
        </div>
    </div>
    
    
    <div class="flex flex-col mt-32 lg:w-1/2 xl:w-1/3 w-full justify-center bg-grey-lightest border border-green-dark rounded-lg shadow-lg overflow-hidden">
        <div class="absolute hidden -translate-50 self-center p-4 justify-center items-center bg-white rounded-full shadow sm:flex" style="margin-top: -128px">
            <img class="h-32 w-32 rounded-full" src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/45/45be9bd313395f74762c1a5118aee58eb99b4688_full.jpg"/>
        </div>
        <div class="flex flex-col p-4 justify-center items-center sm:p-6">
            <div class="flex flex-col self-stretch items-center justify-between sm:flex-row sm:items-start">
                <h2 class="text-grey-dark text-lg font-mono font-medium">#23858283</h2>
                <span class="mt-2 py-2 px-3 text-sm text-green-darkest font-bold bg-green rounded-lg sm:mt-0">PAGO</span>
            </div>
            
            <h2 class="mt-16 uppercase text-grey-dark text-center text-2xl font-normal tracking-wide">Valor total</h2>
            <p class="flex mt-8 mb-4 justify-center items-baseline text-center text-5xl">
                <span class="mr-1 text-3xl text-grey font-normal">R$</span>
                <span class="text-grey-darkest font-semibold">8,00</span>
            </p>
            
            <svg class="my-2 w-16 h-16 fill-current text-green-dark" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                 viewBox="0 0 426.667 426.667" xml:space="preserve">
                <path d="M213.333,0C95.518,0,0,95.514,0,213.333s95.518,213.333,213.333,213.333c117.828,0,213.333-95.514,213.333-213.333S331.157,0,213.333,0z M174.199,322.918l-93.935-93.931l31.309-31.309l62.626,62.622l140.894-140.898l31.309,31.309L174.199,322.918z"/>
            </svg>
            
            
            <a href="#" class="py-4 px-12 text-grey-darker text-xl font-normal no-underline hover:text-grey-darkest hover:underline">‹ Voltar ao VIP-Admin</a>
        </div>
        
        <div class="h-4 w-full">
            <div class="h-full w-full bg-green-dark w-64"></div>
        </div>
    </div>
    
    
    <div class="flex flex-col mt-32 lg:w-1/2 xl:w-1/3 w-full justify-center border border-red-dark bg-grey-lightest rounded-lg shadow-lg overflow-hidden">
        <div class="absolute hidden -translate-50 self-center p-4 justify-center items-center bg-white rounded-full shadow sm:flex" style="margin-top: -128px">
            <img class="h-32 w-32 rounded-full" src="https://steamcdn-a.akamaihd.net/steamcommunity/public/images/avatars/45/45be9bd313395f74762c1a5118aee58eb99b4688_full.jpg"/>
        </div>
        <div class="flex flex-col p-4 justify-center items-center sm:p-6">
            <div class="flex flex-col self-stretch items-center justify-between sm:flex-row sm:items-start">
                <h2 class="text-grey-dark text-lg font-mono font-medium">#23858283</h2>
                <span class="mt-2 py-2 px-3 text-sm text-red-lightest font-bold bg-red rounded-lg sm:mt-0">RECUSADO</span>
            </div>
            
            <h2 class="mt-16 uppercase text-grey-dark text-center text-2xl font-normal tracking-wide">Valor total</h2>
            <p class="flex mt-8 pb-4 justify-center items-baseline text-center text-5xl">
                <span class="mr-1 text-3xl text-grey font-normal">R$</span>
                <span class="text-grey-darkest font-semibold">8,00</span>
            </p>
            
            <svg class="my-2 w-16 h-16 fill-current text-red-dark" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px"
                 viewBox="0 0 426.667 426.667" xml:space="preserve">
                <path d="M213.333,0C95.514,0,0,95.514,0,213.333s95.514,213.333,213.333,213.333s213.333-95.514,213.333-213.333S331.153,0,213.333,0z M330.995,276.689l-54.302,54.306l-63.36-63.356l-63.36,63.36l-54.302-54.31l63.356-63.356l-63.356-63.36l54.302-54.302l63.36,63.356l63.36-63.356l54.302,54.302l-63.356,63.36L330.995,276.689z"/>
            </svg>
            
            
            <a href="#" class="mt-4 py-4 px-12 text-grey-darker text-xl font-normal no-underline hover:text-grey-darkest hover:underline">‹ Voltar ao VIP-Admin</a>
        </div>
        
        <div class="h-4 w-full">
            <div class="h-full w-full bg-red-dark w-64"></div>
        </div>
    </div>
    
    
    <div class="mt-32 lg:w-1/2 xl:w-1/3 w-full">
        <div class="bg-grey-lightest rounded-lg shadow-lg overflow-hidden border border-blue-dark">
            <div class="flex flex-wrap justify-center items-stretch p-4">
                <p class="mb-2 p-4 w-full text-center text-2xl text-grey-darkest">Escolha seu método de pagamento:</p>
                <div class="w-full sm:w-1/2 p-4 text-4xl">
                    <a href="#" class="trans h-32 flex py-3 px-10 sm:px-5 justify-center items-center text-grey-darkest rounded-lg bg-grey-lightest no-underline hover:shadow hover:bg-white">
                        <img class="max-w-full" src="https://logodownload.org/wp-content/uploads/2014/10/paypal-logo.png"/>
                    </a>
                </div>
                <div class="w-full sm:w-1/2 p-4 text-4xl">
                    <a href="#" class="trans h-32 flex py-3 px-10 sm:px-5 justify-center items-center text-grey-darkest rounded-lg bg-grey-lightest no-underline hover:shadow hover:bg-white">
                        <img class="max-w-full" src="http://www.freelogovectors.net/wp-content/uploads/2019/02/Mercadopago-logo.png"/>
                    </a>
                </div>
                <div class="w-full sm:w-1/2 p-4 text-4xl">
                    <a class="opacity-50 trans h-32 flex py-3 px-10 sm:px-5 justify-center items-center text-grey-darkest rounded-lg bg-grey-lightest no-underline">
                        <img class="max-w-full" src="https://cdn-images-1.medium.com/max/1200/1*NarjT54CL02HHKsSiw68zQ.png"/>
                    </a>
                </div>
                <div class="w-full sm:w-1/2 p-4 text-4xl">
                    <a href="#" class="trans h-32 flex py-3 px-10 sm:px-5 justify-center items-center text-grey-darkest rounded-lg bg-grey-lightest no-underline hover:shadow hover:bg-white">
                        <img class="max-w-full" src="https://logodownload.org/wp-content/uploads/2014/09/counter-strike-global-offensive-cs-go-logo.png"/>
                    </a>
                </div>
            </div>
            <div class="h-4 w-full">
                <div class="h-full w-full bg-blue-dark w-64"></div>
            </div>
        </div>
    </div>
    
    <!--
    <div class="fixed pin-t pin-l pin-r flex justify-center w-full h-16">
        <div class="lg:w-1/2 w-full">
            <div class="lg:mt-4 bg-grey-lightest lg:rounded-lg shadow-lg overflow-hidden border-b lg:border border-blue-dark">
                <div class="flex justify-between items-center p-4">
                    <h1 class="flex px-2 flex-col font-light text-base whitespace-no-wrap md:text-xl text-grey-dark"><span>Valor total:</span> <span class="font-normal text-2xl md:text-3xl text-grey-darkest">$2,24</span></h1>
                    <h1 class="flex px-2 flex-col font-light text-base whitespace-no-wrap md:text-xl text-grey-dark"><span>Período total:</span> <span class="font-normal text-2xl md:text-3xl text-grey-darkest">24 dias</span></h1>
                    <a href="#" class="py-4 px-12 bg-blue no-underline text-grey-lighter text-xl font-bold rounded-lg shadow shadow-3d-blue-sm hover:bg-blue-dark">Finalizar</a>
                </div>
            </div>
        </div>
    </div>
    -->
    
    
    <div class="fixed pin-t pin-l pin-r flex justify-center w-full h-16">
        <div class="lg:w-1/2 w-full">
            <div class="lg:mt-4 bg-grey-lightest lg:rounded-lg shadow-lg overflow-hidden border-b lg:border border-blue-dark">
                <div class="flex justify-around items-center p-4">
                    <h1 class="flex px-2 flex-col font-light text-base whitespace-no-wrap md:text-xl text-grey-dark"><span>Valor total:</span> <span class="font-normal text-2xl md:text-3xl text-grey-darkest">$2,24</span></h1>
                    <h1 class="flex px-2 flex-col font-light text-base whitespace-no-wrap md:text-xl text-grey-dark"><span>Período total:</span> <span class="font-normal text-2xl md:text-3xl text-grey-darkest">24 dias</span></h1>
    
                    <div>
                        <svg class="h-12 w-12 mx-2" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" x="0px" y="0px" viewBox="0 0 286.054 286.054" style="enable-background:new 0 0 286.054 286.054;">
                            <path style="fill:#E2574C;"
                                  d="M143.027,0C64.04,0,0,64.04,0,143.027c0,78.996,64.04,143.027,143.027,143.027c78.996,0,143.027-64.022,143.027-143.027C286.054,64.04,222.022,0,143.027,0z M143.027,259.236c-64.183,0-116.209-52.026-116.209-116.209S78.844,26.818,143.027,26.818s116.209,52.026,116.209,116.209S207.21,259.236,143.027,259.236z M143.036,62.726c-10.244,0-17.995,5.346-17.995,13.981v79.201c0,8.644,7.75,13.972,17.995,13.972c9.994,0,17.995-5.551,17.995-13.972V76.707C161.03,68.277,153.03,62.726,143.036,62.726z M143.036,187.723c-9.842,0-17.852,8.01-17.852,17.86c0,9.833,8.01,17.843,17.852,17.843s17.843-8.01,17.843-17.843C160.878,195.732,152.878,187.723,143.036,187.723z"/>
                        </svg>
                    </div>
                    <div class="flex flex-col items-stretch">
                        <p class="break-words uppercase font-medium text-center text-base text-grey-darkest tracking-wide">Valor acima do permitido! E mais um pouco de texto!</p>
                        <small class="mt-2 text-center font-light text-sm text-grey-dark">E mais um pouco pra ver a zica que fica se tem muito texto nessa porra</small>
                    </div>
                    <a href="#" class="hidden py-4 px-12 bg-blue no-underline text-grey-lighter text-xl font-bold rounded-lg shadow shadow-3d-blue-sm hover:bg-blue-dark">Finalizar</a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="mt-32 xl:w-1/2 w-full">
        <div class="bg-grey-lightest rounded-lg shadow-lg overflow-hidden border border-blue-dark">
            <div class="flex flex-wrap justify-start items-stretch p-4">
                <p class="mb-2 p-4 w-full text-center text-2xl text-grey-darkest">Escolha seu método de pagamento:</p>
                <div class="w-full sm:w-1/2 md:w-1/3 p-4 text-4xl">
                    <a href="#" class="trans flex flex-col justify-center items-stretch text-grey-darkest rounded-lg bg-grey-lightest no-underline overflow-hidden shadow-md bg-white">
                        <div class="w-full h-3 bg-blue-dark"></div>
                        <div class="py-3 px-10 sm:px-5">
                            <img class="max-w-full" src="https://steamcommunity-a.akamaihd.net/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpopb3wflFf0Ob3YjoXuY-JlYWZnvb4DLfYkWNFpsQg2LqZotil0QG18kU9amuiddfGJgZoZ1yB_gfrl-vr08e4uZicwXB9-n51wGEO0Qg"/>
                            <h3 class="text-sm font-normal">StatTrak™ <strong>USP-S | Torque</strong> (Field-Tested)</h3>
                            <div class="mt-4 flex justify-between">
                                <span class="text-lg font-semibold">$2.26</span>
                                <span class="text-lg font-semibold">32 dias</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="w-full sm:w-1/2 md:w-1/3 p-4 text-4xl">
                    <a href="#" class="trans flex flex-col justify-center items-stretch text-grey-darkest rounded-lg bg-grey-lightest no-underline overflow-hidden shadow-md bg-white">
                        <div class="w-full h-3 bg-blue-dark"></div>
                        <div class="py-3 px-10 sm:px-5">
                            <img class="max-w-full" src="https://steamcommunity-a.akamaihd.net/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpopb3wflFf0Ob3YjoXuY-JlYWZnvb4DLfYkWNFpsQg2LqZotil0QG18kU9amuiddfGJgZoZ1yB_gfrl-vr08e4uZicwXB9-n51wGEO0Qg"/>
                            <h3 class="text-sm font-normal">StatTrak™ <strong>USP-S | Torque</strong> (Field-Tested)</h3>
                            <div class="mt-4 flex justify-between">
                                <span class="text-lg font-semibold">$2.26</span>
                                <span class="text-lg font-semibold">32 dias</span>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="w-full sm:w-1/2 md:w-1/3 p-4 text-4xl">
                    <a href="#" class="trans flex flex-col justify-center items-stretch text-grey-darkest rounded-lg bg-grey-lightest no-underline overflow-hidden shadow-md bg-white">
                        <div class="w-full h-3 bg-blue-dark"></div>
                        <div class="py-3 px-10 sm:px-5">
                            <img class="max-w-full" src="https://steamcommunity-a.akamaihd.net/economy/image/-9a81dlWLwJ2UUGcVs_nsVtzdOEdtWwKGZZLQHTxDZ7I56KU0Zwwo4NUX4oFJZEHLbXH5ApeO4YmlhxYQknCRvCo04DEVlxkKgpopb3wflFf0Ob3YjoXuY-JlYWZnvb4DLfYkWNFpsQg2LqZotil0QG18kU9amuiddfGJgZoZ1yB_gfrl-vr08e4uZicwXB9-n51wGEO0Qg"/>
                            <h3 class="text-sm font-normal">StatTrak™ <strong>USP-S | Torque</strong> (Field-Tested)</h3>
                            <div class="mt-4 flex justify-between">
                                <span class="text-lg font-semibold">$2.26</span>
                                <span class="text-lg font-semibold">32 dias</span>
                            </div>
                        </div>
                    </a>
                </div>
                <a href="#" class="mt-8 py-4 px-12 w-full text-center bg-blue no-underline text-grey-lighter text-xl font-bold rounded-lg shadow shadow-3d-blue-sm hover:bg-blue-dark">Finalizar</a>
            </div>
            
            <div class="h-4 w-full">
                <div class="h-full w-full bg-blue-dark w-64"></div>
            </div>
        </div>
    </div>


</div>
</body>
</html>