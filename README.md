composer create-project symfony/skeleton:"6.4.*" aksamV2
composer install 
composer require webapp 
php -S localhost:8000 -t public
https://symfony.com/doc/current/the-fast-track/en/3-zero.html#initializing-the-project
 
## Configuration webpack:
rm -rf public/build
yarn encore dev
npm run build
yarn remove bootstrap
yarn add bootstrap
tree
rm -rf node_modules yarn.lock
yarn install
yarn add file-loader@^6.0.0 --dev
rm -rf node_modules/.cache
 
nano assets/bootstrap.js
yarn add @symfony/stimulus-bundle --dev
yarn add stimulus --dev
composer require symfony/stimulus-bundle
nano assets/bootstrap.js
yarn --version
yarn add @symfony/webpack-encore --dev


## make user story:
-toutdabord il faut cree une entitie userStrory pour nous stocker les relation entre les team et user avec affect date 
- sur controller on a jouter :

    #[Route('/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($password);


            foreach ($user->getTeams() as $team) {
                $history = new userHistory();
                $history->setUsers($user);
                $history->setTeam($team);
                $history->setAffectAt(new \DateTime());

                $entityManager->persist($history);
            }

            $entityManager->persist($user);
            $entityManager->flush();


- on ajouter script de userHistory sur le fonction de modification user aussi 

##  creat Api:
    on debuter par l'installation a l'aide du cmd : composer require api 