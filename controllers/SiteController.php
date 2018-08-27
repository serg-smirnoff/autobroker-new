<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

use directapi\DirectApiService;
use directapi\services\campaigns\criterias\CampaignsSelectionCriteria;
use directapi\services\campaigns\enum\CampaignStateEnum;
use directapi\services\campaigns\enum\CampaignFieldEnum;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        
		/*
		ID: 27c2b091b1af487c99241638be70f18c
		Пароль: 2d215bdbf07d4e6385df4c9c4821155e
		Callback URL: https://oauth.yandex.ru/verification_code		
		qAuth: AQAAAAAByHq0AAKWHwI2ITh4VESdlp-3BxPVzTI		
		*/
		
		
		/*
		Версия 4
		Взаимодействие с Песочницей возможно в формате JSON и по протоколу SOAP.

		Адрес для запросов JSON:

		https://api-sandbox.direct.yandex.ru/v4/json/
		По следующему адресу находится WSDL Песочницы для взаимодействия по протоколу SOAP:

		https://api-sandbox.direct.yandex.ru/v4/wsdl/ 
		SOAP-запросы отправляют на адрес:

		https://api-sandbox.direct.yandex.ru/v4/soap/
		Версия Live 4
		Взаимодействие с Песочницей возможно в формате JSON и по протоколу SOAP.

		Адрес для запросов JSON:

		https://api-sandbox.direct.yandex.ru/live/v4/json/
		По следующему адресу находится WSDL Песочницы для взаимодействия по протоколу SOAP:

		https://api-sandbox.direct.yandex.ru/live/v4/wsdl/
		SOAP-запросы отправляют на адрес:

		https://api-sandbox.direct.yandex.ru/live/v4/soap/		
		*/
		
		$token = "AQAAAAAByHq0AAKWHwI2ITh4VESdlp-3BxPVzTI";
		$login = "programmatore@yandex.ru";

		$client = new DirectApiService($token, $login);

		$campaigns = $client
			->setSandbox()
			->getCampaignsService()
			->get(
				new CampaignsSelectionCriteria(
					[   
						'States' => [
							CampaignStateEnum::ON
						]   
					]   
				),  
				CampaignFieldEnum::getValues()
			);  
				
		foreach ($campaigns as $campaign) { 
		
			$campaignName = $campaign->Name;
			$negativeKeywords = $campaign->NegativeKeywords;
		
		}
		
		return $this->render('index', array(
			'campaignName' => $campaignName,
			'negativeKeywords' => $negativeKeywords,
		));
		
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
