<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\Testing;

class DeviceSession extends \Google\Collection
{
  protected $collection_key = 'stateHistories';
  /**
   * @var string
   */
  public $activeStartTime;
  protected $androidDeviceType = AndroidDevice::class;
  protected $androidDeviceDataType = '';
  protected $androidDeviceListType = AndroidDeviceList::class;
  protected $androidDeviceListDataType = '';
  /**
   * @var string
   */
  public $createTime;
  /**
   * @var string
   */
  public $displayName;
  /**
   * @var string
   */
  public $expireTime;
  /**
   * @var string
   */
  public $inactivityTimeout;
  /**
   * @var string
   */
  public $name;
  /**
   * @var string
   */
  public $state;
  protected $stateHistoriesType = SessionStateEvent::class;
  protected $stateHistoriesDataType = 'array';
  /**
   * @var string
   */
  public $ttl;

  /**
   * @param string
   */
  public function setActiveStartTime($activeStartTime)
  {
    $this->activeStartTime = $activeStartTime;
  }
  /**
   * @return string
   */
  public function getActiveStartTime()
  {
    return $this->activeStartTime;
  }
  /**
   * @param AndroidDevice
   */
  public function setAndroidDevice(AndroidDevice $androidDevice)
  {
    $this->androidDevice = $androidDevice;
  }
  /**
   * @return AndroidDevice
   */
  public function getAndroidDevice()
  {
    return $this->androidDevice;
  }
  /**
   * @param AndroidDeviceList
   */
  public function setAndroidDeviceList(AndroidDeviceList $androidDeviceList)
  {
    $this->androidDeviceList = $androidDeviceList;
  }
  /**
   * @return AndroidDeviceList
   */
  public function getAndroidDeviceList()
  {
    return $this->androidDeviceList;
  }
  /**
   * @param string
   */
  public function setCreateTime($createTime)
  {
    $this->createTime = $createTime;
  }
  /**
   * @return string
   */
  public function getCreateTime()
  {
    return $this->createTime;
  }
  /**
   * @param string
   */
  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  /**
   * @return string
   */
  public function getDisplayName()
  {
    return $this->displayName;
  }
  /**
   * @param string
   */
  public function setExpireTime($expireTime)
  {
    $this->expireTime = $expireTime;
  }
  /**
   * @return string
   */
  public function getExpireTime()
  {
    return $this->expireTime;
  }
  /**
   * @param string
   */
  public function setInactivityTimeout($inactivityTimeout)
  {
    $this->inactivityTimeout = $inactivityTimeout;
  }
  /**
   * @return string
   */
  public function getInactivityTimeout()
  {
    return $this->inactivityTimeout;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param string
   */
  public function setState($state)
  {
    $this->state = $state;
  }
  /**
   * @return string
   */
  public function getState()
  {
    return $this->state;
  }
  /**
   * @param SessionStateEvent[]
   */
  public function setStateHistories($stateHistories)
  {
    $this->stateHistories = $stateHistories;
  }
  /**
   * @return SessionStateEvent[]
   */
  public function getStateHistories()
  {
    return $this->stateHistories;
  }
  /**
   * @param string
   */
  public function setTtl($ttl)
  {
    $this->ttl = $ttl;
  }
  /**
   * @return string
   */
  public function getTtl()
  {
    return $this->ttl;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DeviceSession::class, 'Google_Service_Testing_DeviceSession');
