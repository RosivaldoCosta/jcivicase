//----------------------------------------------------------------------------------------
// patterns & practices - Smart Client Software Factory - Guidance Package
//
// This file was generated by the "Add View" recipe.
//
// A presenter calls methods of a view to update the information that the view displays. 
// The view exposes its methods through an interface definition, and the presenter contains
// a reference to the view interface. This allows you to test the presenter with different 
// implementations of a view (for example, a mock view).
//
// For more information see:
// ms-help://MS.VSCC.v80/MS.VSIPCC.v80/ms.practices.scsf.2007may/SCSF/html/02-09-010-ModelViewPresenter_MVP.htm
//
// Latest version of this Guidance Package: http://go.microsoft.com/fwlink/?LinkId=62182
//----------------------------------------------------------------------------------------

using System;
using Microsoft.Practices.ObjectBuilder;
using Microsoft.Practices.CompositeUI;
using Sante.EMR.SmartClient.Infrastructure.Interface;
using Microsoft.Practices.CompositeUI.SmartParts;
using Sante.EMR.SmartClient.Infrastructure.Data.Services;
using System.Collections.Generic;
using Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities;

namespace Sante.EMR.SmartClient.Module.NewCase
{
    public partial class NewCaseViewPresenter : Presenter<INewCaseView>
    {
        private IDataAccessService _dataService;

        public NewCaseViewPresenter([ServiceDependency] IDataAccessService dataacessservice)
        {
            _dataService = dataacessservice;

        }

        public void BindToSearchResults(List<Patient> patients)
        {
            View.BindToSearchResults(patients);
        }

        /// <summary>
        /// This method is a placeholder that will be called by the view when it has been loaded.
        /// </summary>
        public override void OnViewReady()
        {
            base.OnViewReady();
        }

        /// <summary>
        /// Close the view
        /// </summary>
        public void OnCloseView()
        {
            base.CloseView();
        }

        public ISmartPartInfo GetSmartPartInfo(Type smartPartInfoType)
        {
            ISmartPartInfo result =
            (ISmartPartInfo)Activator.CreateInstance(smartPartInfoType);
            result.Title = "New Case";
            result.Description = "Create a New Case";

            return result;

        }

        /// <summary>
        /// 
        /// </summary>
        /// <param name="args"></param>
        /// <returns></returns>
        internal List<Patient> Search(string first,string last,string phone, string address)
        {

            if (first != null)
            {
                if (last != null)
                {
                    if (phone != null)
                    {
                        if (address != null)
                            return _dataService.Search(first, last, phone, address);
                        else
                            return _dataService.Search(first, last, phone, null);
                    }
                    else
                    {
                        if (address != null)
                            return _dataService.Search(first, last, null, address);
                        else
                            return _dataService.Search(first, last, null, null);

                    }
                }
                else
                {
                    if (phone != null)
                    {
                        if (address != null)
                            return _dataService.Search(first, null, phone, address);
                        else
                            return _dataService.Search(first, null, phone, null);
                    }
                    else
                    {
                        if (address != null)
                            return _dataService.Search(first, null, null, address);
                        else
                            return _dataService.Search(first, null, null, null);

                    }
                }
            }
            else
            {
                if (last != null)
                {
                    if (phone != null)
                    {
                        if (address != null)
                            return _dataService.Search(null, last, phone, address);
                        else
                            return _dataService.Search(null, last, phone, null);
                    }
                    else
                    {
                        if (address != null)
                            return _dataService.Search(null, last, null, address);
                        else
                            return _dataService.Search(null, last, null, null);

                    }
                }
                else
                {
                    if (phone != null)
                    {
                        if (address != null)
                            return _dataService.Search(null, null, null, address);
                        else
                            return _dataService.Search(null, null, phone, null);
                    }
                    else
                    {
                        if (address != null)
                            return _dataService.Search(null, null, null, address);

                    }
                }
            }

            return _dataService.Search(null, null, null, null);
        }

        
    }
}

