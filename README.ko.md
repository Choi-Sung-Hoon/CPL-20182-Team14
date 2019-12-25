CPL-20182-Team14
==========

이 프로젝트는 Google Cloud Platform에서 Kubernetes의 로드밸런싱 성능에 대해 연구합니다.  

README: [한국어](README.ko.md), [English](README.md)  

</br>

학부생 논문 주제
----------

[쿠버네티스의 클라우드 부하 분산 서비스와 외부 부하 분산 서비스의 성능 비교](http://www.riss.kr/search/detail/DetailView.do?p_mat_type=1a0202e37d52c72d&control_no=e456c06d189a5efb7ecd42904f0c5d65)  

</br>

요약
----------

본 논문은 쿠버네티스를 이용하여 여러 노드에 웹 애플리케이션을 배포하고, 클라우드 로드밸런서와 nginx의 부하 분산 성능을 비교한다.  

쿠버네티스는 컨테이너 기반 애플리케이션의 배포, 확장, 관리를 위한 오픈소스 시스템으로 다양한 기능을 제공한다.  

본 연구에서는 그 중 부하 분산 기능에 관하여 연구하였다.  

구글 클라우드 플랫폼을 활용하여 1개의 동일한 웹 애플리케이션 WordPress를 3개의 노드에 배치하는 두 개의 쿠버네티스 클러스터를 구축하였다.  

첫 번째 클러스터에는 Service 타입을 **LoadBalancer**로 설정하여 구글 클라우드 플랫폼에서 제공하는 **기본 로드밸런서**를 사용하였고, 두 번째 클러스터에는 웹서버 애플리케이션인 nginx를 사용하여 **nginx에서 제공하는 로드 밸런서**를 사용하였다.

그리고 **Apache Bench**를 사용하여 각 클러스터의 부하 분산 성능을 비교하였다.

</br>

시스템 개요도
----------

- ### 클러스터 A

    클러스터(A)는 웹 애플리케이션 Pod들을 Label을 통해 **LoadBalancer** 타입 서비스로 그룹화하고, 구글 클라우드 플랫폼에서 제공하는 로드밸런서를 사용한 부하 분산 환경을 구축하여 해당 서비스를 외부로 노출시킨다.  
    
    ![Cluster_A](https://user-images.githubusercontent.com/33472400/71443042-0dc16f80-274c-11ea-8050-7dfbef7eafb1.png)
    
- ### 클러스터 B

    클러스터(B)에서는 웹 애플리케이션 Pod들을 Label을 통해 **NodePort** 타입 서비스로 그룹화하고, 같은 클러스터 내에 있는 웹 서버 애플리케이션인 nginx를 사용하여 웹 애플리케이션이 실행되는 3개의 노드로 부하가 분산되게 설정한다.  
    
    마지막으로nginx 애플리케이션을 **LoadBalancer** 서비스로 설정하여 외부로 노출시킨다.  
    
    ![Cluster_B](https://user-images.githubusercontent.com/33472400/71443470-74478d00-274e-11ea-8f93-8f733e8ced5b.png)
    
</br>

테스트 결과
----------

- **Google Cloud Platform load balancer:** ![GCP_Load_Balancer](https://user-images.githubusercontent.com/33472400/71448574-0a94a680-2781-11ea-9619-ef1bd72bdd50.png)  

- **nginx Load balancer:** ![image](https://user-images.githubusercontent.com/33472400/71448583-36179100-2781-11ea-9d97-9bcf4508b18a.png)  

</br>

1. 16개의 Java thread pool을 사용하여 클러스터 (A), (B)에게 각각 10000번의 request를 보낸 결과  

    ![Test_Result_1](https://user-images.githubusercontent.com/33472400/71448555-aeca1d80-2780-11ea-9d20-1a69d6521142.png)  

2. 64개의 Java thread pool을 이용하여 클러스터 (A), (B)에서 각각 10000번의 request를 보낸 결과  
  
    ![Test_Result_2](https://user-images.githubusercontent.com/33472400/71448608-8a227580-2781-11ea-9f6c-49839325574f.png)

</br>

데모 영상
----------

[<img src="http://img.youtube.com/vi/vv6oecM3YpE/0.jpg" width="600">](http://www.youtube.com/watch?v=vv6oecM3YpE)
