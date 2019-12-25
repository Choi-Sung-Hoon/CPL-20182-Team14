CPL-20182-Team14
==========

This project is to research the performance of load balancer of Kubernetes on Google Cloud Flatform.  

README: [한국어](README.ko.md), [English](README.md)  

</br>

Paper Topic
----------

[Performance comparison between a cloud load balancer and an external load balancer on Kubernetes.](http://www.riss.kr/search/detail/DetailView.do?p_mat_type=1a0202e37d52c72d&control_no=e456c06d189a5efb7ecd42904f0c5d65)  

</br>

Summary
----------

This project deploys a web application on multiple nodes and compares performance of cloud load balancer and nginx load balancer using Kubernetes.  

Kubernetes supports various features as an open source system for deploying, scaling and managing of container-based application.  

This project researches on performance of load balancing.  

We constructed 2 Kubernetes clusters which deploy WordPress web application on 3 nodes using Google Cloud Platform.  

First cluster uses **standard load balancer** which Google Cloud Platform provides, setting Service type to **LoadBalancer** and second cluster uses **load balancer from nginx** which is web server application.

At last, we compared the performance of load balancing of each cluster using **Apache Bench**.

</br>

System Architecture
----------

- ### Cluster A

    Cluster(A) forms a group as **LoadBalancer** type with web application Pods via Label, it constructs load balancing environment using load balancer from Google Cloud Platform and exposes the service.
    
    ![Cluster_A](https://user-images.githubusercontent.com/33472400/71443042-0dc16f80-274c-11ea-8050-7dfbef7eafb1.png)
    
- ### Cluster B
    
    Cluster(B) forms a group as **NodePort** type with web application Pods via Label, it uses nginx to balance loads to 3 nodes where web application runs.
    
    At last, it sets nginx application to **LoadBalancer** service and exposes.

    ![Cluster_B](https://user-images.githubusercontent.com/33472400/71443470-74478d00-274e-11ea-8f93-8f733e8ced5b.png)

</br>

Test Result
----------

- **Google Cloud Platform load balancer:** ![GCP_Load_Balancer](https://user-images.githubusercontent.com/33472400/71448574-0a94a680-2781-11ea-9619-ef1bd72bdd50.png)  

- **nginx Load balancer:** ![image](https://user-images.githubusercontent.com/33472400/71448583-36179100-2781-11ea-9d97-9bcf4508b18a.png)  

</br>

1. Result of sending 10000 times of requests to cluster (A), (B) using 16 of Java thread pools.

    ![Test_Result_1](https://user-images.githubusercontent.com/33472400/71448555-aeca1d80-2780-11ea-9d20-1a69d6521142.png)  
  
  
2. Result of sending 10000 times of requests to cluster (A), (B) using 64 of Java thread pools.
  
    ![Test_Result_2](https://user-images.githubusercontent.com/33472400/71448608-8a227580-2781-11ea-9f6c-49839325574f.png)
    
</br>

Demo Video
----------

[<img src="http://img.youtube.com/vi/vv6oecM3YpE/0.jpg" width="600">](http://www.youtube.com/watch?v=vv6oecM3YpE)
