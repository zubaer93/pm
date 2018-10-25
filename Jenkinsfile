node {
  stage("Checking Out Git") {
      checkout scm
  }
  stage("SFTP Files to Dev Server"){
    sshagent(['7151e1c5-7353-40b8-99c9-df066feeb495']) {
		sh "echo running"
		sh "whoami"
		sh "scp -r ** ubuntu@18.216.32.188:/var/www/stockgitter"
	}
  }
  stage("Composer Install"){
		sh "ssh -t ubuntu@18.216.32.188 /var/www/stockgitter/composer install"
  }
  stage("Run Migrations"){
	sshagent(['7151e1c5-7353-40b8-99c9-df066feeb495']) {
		sh "ssh -t ubuntu@18.216.32.188 /var/www/stockgitter/bin/cake migrations migrate -p CakeDC/Users"
		sh "ssh -t ubuntu@18.216.32.188 /var/www/stockgitter/bin/cake migrations migrate"
		sh "ssh -t ubuntu@18.216.32.188 /var/www/stockgitter/bin/cake migrations seed --seed CountriesSeed"
		sh "ssh -t ubuntu@18.216.32.188 /var/www/stockgitter/bin/cake migrations seed --seed ExchangesSeed"
		sh "ssh -t ubuntu@18.216.32.188 /var/www/stockgitter/bin/cake migrations seed --seed ExperienceSeed"
		sh "ssh -t ubuntu@18.216.32.188 /var/www/stockgitter/bin/cake migrations seed --seed InvestmentStyleSeed"
		sh "ssh -t ubuntu@18.216.32.188 /var/www/stockgitter/bin/cake companies import usa"
		sh "ssh -t ubuntu@18.216.32.188 /var/www/stockgitter/bin/cake companies import jam"
		sh "ssh -t ubuntu@18.216.32.188 /var/www/stockgitter/bin/cake stocks update_prices"
		sh "ssh -t ubuntu@18.216.32.188 /var/www/stockgitter/bin/cake news main"
	}
  }
  //stage("Change Ownership and Permissions of Files"){
   //sshagent(['andrew_pem']) {
	//	sh "ssh -t andrew@13.57.14.97 sudo chown -R iziphub:iziphub /home/iziphub/public_html/*"
	//	sh "ssh -t andrew@13.57.14.97 sudo find . -type f -exec chmod 644 {} +"
	//}
  //}
}