<?php
  namespace  App\Handles\ImageUploadHandle;
  use Illuminate\Support\Str;
  class ImageUploadHandle
  {
    public function save($file , $folder , $user)
    {
      // 先设置一个文件存放的路径，一般存在public 或者 storage 下，这里我把它存在 storage 下
      // public_path() 获取项目的 public 的物理路径，从服务器的跟路径开始，
      $allowed_ext = ['jpg' , 'png' , 'jpeg'];
      // 获取图片的后缀,如果文件后缀存在，即为它自己，如果不存在，设置图片后缀为 png，图片名字等会自己定义，所有后缀等会用来拼接
      $file_path = public_path() .'/storage/face/'.$folder.'/'.$user->uid;
      // 设置图片的名字,名字为当前的时间+随机字符串10个+刚才获取的后缀
      $ext = strtolower($file->getClientOriginalExtension()) ? : 'png';
      // 判断后缀是否是允许的，如果获取到文件的后缀，而不是自己设定的png ，就要对其进行判断，是不是图片, 不在返回 false 结束
      $str_random = Str::random(10);
      $file_name = time().$str_random.'/'.$ext;
      if(!in_array($ext , $this->allowed_ext))
      {
        return false;
      }
      //存储图片，意思：参数一：路径 参数二：名字，把文件存到这个路径下，使用这个名字保存。
      $file->move($file_path, $file_name);
      // 返回一个路径用于存入数据库，asset 用于获取文件的链接，结果为字符串。
      return asset("storage/face/.{$folder}.'/'.{$user->uid}.'/'.{$file_name}'");
    }
  }
?>